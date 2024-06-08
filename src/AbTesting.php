<?php

namespace folez\LaravelAb;

use folez\LaravelAB\Contracts\VisitorInterface;
use folez\LaravelAB\Events\GoalCompleted;
use folez\LaravelAB\Events\VariantNewVisitor;
use folez\LaravelAB\Exceptions\InvalidConfiguration;
use folez\LaravelAB\Models\AbGoal;
use folez\LaravelAB\Models\AbVariant;
use folez\LaravelAB\Models\AbVisitor;
use Illuminate\Database\Eloquent\Collection;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class AbTesting
{
    public const SESSION_KEY_GOALS = 'ab_testing_goals';

    protected Collection|array $variants;

    protected ?VisitorInterface $visitor = null;

    public function __construct()
    {
        $this->variants = new Collection();
    }

    /**
     * Resets the visitor data.
     */
    public function resetVisitor(): void
    {
        session()->flush();
        $this->visitor = null;
    }

    /**
     * Triggers a new visitor. Picks a new variant and saves it to the Visitor.
     *
     *
     */
    public function pageView(string|int|null $visitorId = null): false|AbVariant
    {
        if (config('laravel-ab.ignore_crawlers') && (new CrawlerDetect())->isCrawler()) {
            return false;
        }

        $visitor = $this->getVisitor($visitorId);

        if (! session(self::SESSION_KEY_GOALS) || $this->variants->isEmpty()) {
            $this->start();
        }

        if ($visitor->hasVariant()) {
            return $visitor->getVariant();
        }

        $this->setNextVariant($visitor);

        event(new VariantNewVisitor($this->getVariant(), $visitor));

        return $this->getVariant();
    }

    /** Checks if the currently active variant is the given one. */
    public function isVariant(string $name): bool
    {
        $variant = $this->pageView();

        if (! $variant) {
            return false;
        }

        return $variant->name === $name;
    }

    /** Completes a goal by incrementing the hit property of the model and setting its ID in the session. */
    public function completeGoal(string $goal, string|int|null $visitorId = null): false|AbGoal
    {
        $variant = $this->pageView($visitorId);

        if (! $variant) {
            return false;
        }

        /** @var AbGoal $goal */
        $goal = $this->getVariant($visitorId)->goals->where('name', $goal)->first();

        if (! $goal) {
            return false;
        }

        if (session(self::SESSION_KEY_GOALS)->contains($goal->id)) {
            return false;
        }

        session(self::SESSION_KEY_GOALS)->push($goal->id);

        $goal->incrementHit();
        event(new GoalCompleted($goal));

        return $goal;
    }

    /** Returns the currently active variant. */
    public function getVariant(string|int|null $visitorId = null): ?AbVariant
    {
        return $this->getVisitor($visitorId)->getVariant();
    }

    /** Returns all the completed goals. */
    public function getCompletedGoals(): false|AbGoal
    {
        if (! session(self::SESSION_KEY_GOALS)) {
            return false;
        }

        return session(self::SESSION_KEY_GOALS)->map(function ($goalId) {
            return AbGoal::find($goalId);
        });
    }

    /**
     * Returns a visitor instance. Sugestion: use uniqid() as visitor_id.
     */
    public function getVisitor(string|int|null $visitorId = null): VisitorInterface
    {
        if (! is_null($this->visitor)) {
            return $this->visitor;
        }

        if (! empty($visitorId) && config('laravel-ab.use_database')) {
            return $this->visitor = AbVisitor::firstOrNew(['visitor_id' => $visitorId]);
        }

        return $this->visitor = new SessionVisitor();
    }

    /** Calculates a new variant and sets it to the Visitor. */
    protected function setNextVariant(VisitorInterface $visitor): void
    {
        $variant = $this->getNextVariant();
        $variant->incrementVisitor();

        $visitor->setVariant($variant);
    }

    /** Calculates a new variant. */
    protected function getNextVariant(): AbVariant
    {
        $sorted = $this->variants->sortBy('visitors');

        return $sorted->first();
    }

    /**
     * Validates the config items and puts them into models.
     */
    protected function start(): void
    {
        $configVariants = config('laravel-ab.variants');
        $configGoals = config('laravel-ab.goals');

        if (! count($configVariants)) {
            throw InvalidConfiguration::noVariant();
        }

        if (count($configVariants) !== count(array_unique($configVariants))) {
            throw InvalidConfiguration::variant();
        }

        if (count($configGoals) !== count(array_unique($configGoals))) {
            throw InvalidConfiguration::goal();
        }

        foreach ($configVariants as $configVariant) {
            $this->variants[] = $variant = AbVariant::with('goals')->firstOrCreate([
                'name' => $configVariant,
            ], [
                'visitors' => 0,
            ]);

            foreach ($configGoals as $configGoal) {
                $variant->goals()->firstOrCreate([
                    'name' => $configGoal,
                ], [
                    'hit' => 0,
                ]);
            }
        }

        session([
            self::SESSION_KEY_GOALS => new Collection(),
        ]);
    }
}
