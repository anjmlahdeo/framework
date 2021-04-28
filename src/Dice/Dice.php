<?php

declare(strict_types=1);

namespace Rois\Dice;

class Dice
{
    private int $sides = 6;
    protected int $rollResult = 0;

    /**
     * Constructor to initialize the dice with all 6 sides.
     */
    public function __construct(int $diceSides)
    {
        $this->sides = $diceSides;
    }

    /**
     * Sets dice to be of specified value.
     */
    public function setDiceValue($val): void
    {
        $this->rollResult = $val;
    }

    /**
     * Roll this dice to retrieve its value.
     * @return int The rsulting value from roll of this dice.
     */
    public function roll(): int
    {
        $this->rollResult = rand(1, $this->sides);

        return $this->rollResult;
    }

    /**
     * Retrieves the result from the dice roll.
     * @return int Retrieves the result from the dice roll.
     */
    public function getLastRoll(): int
    {
        return $this->rollResult;
    }
}
