<?php

declare(strict_types=1);

namespace Rois\Dice;

class GraphicalDice extends Dice
{
    /**
     * @var int - SIDES, the number of sides of the dice.
     */
    const SIDES = 6;
    /**
     * Constructor to initialize the dice with all 6 sides.
     */
    public function __construct()
    {
        parent::__construct($this::SIDES);
    }

    /**
     * Function to print a graphical representation of the dice.
     */
    public function graphic(): string
    {
        return "dice-" . $this->rollResult;
    }
}
