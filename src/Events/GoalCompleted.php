<?php

namespace FoLez\LaravelAB\Events;

use FoLez\LaravelAB\Models\AbGoal;

class GoalCompleted
{
    public function __construct(public AbGoal $goal)
    {
    }
}
