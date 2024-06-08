<?php

namespace folez\LaravelAB\Events;

use folez\LaravelAB\Models\AbGoal;

class GoalCompleted
{
    public function __construct(public AbGoal $goal)
    {
    }
}
