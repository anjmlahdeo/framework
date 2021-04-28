<?php

declare(strict_types=1);

namespace Rois\Dice;

class DiceHand
{
    /**
     * @var dices Array containing dices.
     * @var values Array containing dice roll values.
     */
    private array $dices;
    private array $values;

    /**
     * Constructor to initialize DeceHand with a number of Dices.
     * @var int $numDices - Number of dices.
     */
    public function __construct(int $numDices)
    {
        $this->dices = [];
        $this->values = [];

        for ($i = 0; $i < $numDices; $i++) {
            $this->dices[] = new GraphicalDice();
            $this->values[] = null;
        }
    }

    /**
     * Rolls all dices in the hand and save values.
     * @return void
     */
    public function roll(): void
    {
        $this->values = [];
        foreach ($this->dices as $dice) {
            $dice->roll();
            array_push($this->values, $dice->getLastRoll());
        }
    }


    /**
     * Removes the selected dices and their values from this DiceHand.
     * @return void
     */
    public function spliceDice($index): void
    {
        array_splice($this->values, $index, 1);
        array_splice($this->dices, $index, 1);
    }

    /**
     * Adds dice with specified value to the DiceHand.
     */
    public function addDice($value): void
    {
        $newDice = new GraphicalDice();
        $newDice->setDiceValue($value);
        array_push($this->dices, $newDice);
        array_push($this->values, $value);
    }

    /**
     * Get the values from all dices from last roll.
     * @return array - Array of resulting dice values from last roll.
     */
    public function values(): array
    {
        return $this->values;
    }

    /**
     * Calculate the sum of all values from last roll.
     * @return int - The sum of all dice values from last roll.
     */
    public function sum(): int
    {
        return array_sum($this->values);
    }

    /**
     * Getter for the dices held by this DiceHand
     * @return array
     */
    public function getDices(): array
    {
        return $this->dices;
    }
}
