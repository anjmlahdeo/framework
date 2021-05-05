<?php

declare(strict_types=1);

namespace Rois\Yatzy;

use Rois\Yatzy\Round;

class YatzyGame
{
    const THROW_LIMIT = 3;
    private array $targets = array(
        1 => "",
        2 => "",
        3 => "",
        4 => "",
        5 => "",
        6 => ""
    );
    private int $roundCount = 1;
    private ?Round $currentRound = null;
    private bool $endGame = false;

    /**
     * Initializes game with a new Round Object.
     * @return void
     */
    public function initGame(): void
    {
        $this->currentRound = new Round($this::THROW_LIMIT);
    }

    /**
     * Creates a new round and increases roundCount
     */
    public function newRound(): void
    {
        $this->roundCount++;
        $this->currentRound = new Round($this::THROW_LIMIT);
    }

    /**
     * Saves round result to the specified target.
     */
    public function saveRound($target, $test = null): void
    {
        if ($this->targets[$target] === "") {
            $this->targets[$target] = $this->currentRound->getRoundResult($target);
        }
        if ($test || $this->roundCount === 6) {
            $this->endGame = true;
        }
    }

    /**
     * Getter for $targets.
     */
    public function getRounds(): array
    {
        return $this->targets;
    }

    /**
     * Getter for currentRound
     */
    public function getCurrentRound(): Round
    {
        return $this->currentRound;
    }

    /**
     * Getter for current roundCount.
     */
    public function getRoundCount(): int
    {
        return $this->roundCount;
    }

    /**
     * Getter for total game score.
     */
    public function getTotalScore(): int
    {
        return array_sum(array_values($this->targets));
    }

    /**
     * Check if game has been marked as ended.
     */
    public function checkEndGame(): bool
    {
        return $this->endGame;
    }
}
