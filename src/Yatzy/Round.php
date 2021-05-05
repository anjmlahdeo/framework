<?php

declare(strict_types=1);

namespace Rois\Yatzy;

use Rois\Dice\DiceHand;

class Round
{
    private $throwLimit;
    private DiceHand $diceHand;
    private array $storedDices = [];
    private int $throwCount = 0;
    private bool $end = false;
    private bool $saved = false;

    /**
     * Constructor to initialize the round.
     */
    public function __construct(int $throwLimit)
    {
        $this->throwLimit = $throwLimit;
        $this->diceHand = new DiceHand(5);
    }

    public function roll(): void
    {
        $this->saved = false;
        $this->diceHand->roll();
        $this->throwCount++;
        if ($this->throwCount === 3) {
            $this->end = true;
        }
    }

    public function getDiceHand(): DiceHand
    {
        return $this->diceHand;
    }

    public function storeDices($index): void
    {
        array_push($this->storedDices, $this->diceHand->values()[$index]);
        $this->diceHand->spliceDice($index);

        $this->saved = true;
    }

    public function removeDices($index): void
    {
        $this->diceHand->addDice($this->storedDices[$index]);
        array_splice($this->storedDices, $index, 1);
        $this->saved = true;
    }

    public function getStoredDices(): array
    {
        return $this->storedDices;
    }

    public function checkSaved(): bool
    {
        return $this->saved;
    }

    public function checkEnd(): bool
    {
        return $this->end;
    }

    public function getRoundResult($value): int
    {
        $sum = 0;
        foreach ($this->storedDices as $dice) {
            if ($dice === $value) {
                $sum += $dice;
            }
        }
        return $sum;
    }

    public function getThrowCount(): int
    {
        return $this->throwCount;
    }
}
