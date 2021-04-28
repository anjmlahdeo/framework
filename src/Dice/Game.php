<?php

declare(strict_types=1);

namespace Rois\Dice;

use function Mos\Functions\{
    renderView,
    sendResponse,
    redirectTo,
    url
};

class Game
{

    private $diceHand;
    private int $sum = 0;
    private ?string $result = null;
    private int $rollCount = 0;

    /**
     * Generates a new dicehand and makes the first roll.
     * @return void
     */
    public function playGame($dices): void
    {
        $this->diceHand = new DiceHand($dices);
        $this->diceHand->roll();
        $this->sum += $this->diceHand->sum();
        $this->rollCount += 1;
    }

    /**
     * Makes a new roll of the dices.
     * @return void
     */
    public function newRoll($testSum = null): void
    {
        $this->diceHand->roll();
        $this->sum = $testSum ? $testSum : $this->sum + $this->diceHand->sum();
        if ($this->sum > 21) {
            $this->result = "You Lost!";
            $this->saveRound("computer");
        } elseif ($this->sum === 21) {
            $this->result = "You win!";
            $this->saveRound("player");
        }
        $this->rollCount += 1;
    }

    /**
     * Make computer dice rolls and generate a result.
     * $testPlayerScore Score for player for test purposes only.
     * $testComputerScore Score for computer for test purposes only.
     * @return void
     */
    public function stop($testPlayerScore = null, $testComputerScore = null): void
    {
        $computerHand = new DiceHand(count($this->getDices()));
        $computerSum = 0;

        while ($computerSum < 15) {
            $computerHand->roll();
            $computerSum += $computerHand->sum();
        }

        if ($testPlayerScore && $testComputerScore) {
            $this->sum = $testPlayerScore;
            $computerSum = $testComputerScore;
        }

        if ($computerSum >= $this->sum && $computerSum <= 21) {
            $this->result = "You lost! Computers score was: " . $computerSum;
            $this->saveRound("computer");
            return;
        }

        $this->result = "You win! Computers score was: " . $computerSum;
        $this->saveRound("player");
    }

    /**
     * Getter to retrieve the dices.
     * @return array The dice objects after latest roll.
     */
    public function getDices(): array
    {
        return $this->diceHand->getDices();
    }

    /**
     * Gets the values of the dices.
     * Getter to retrieve dice values.
     * @return array The dice values from latest roll.
     */
    public function getValues(): array
    {
        return $this->diceHand->values();
    }

    /**
     * Getter to retrieve the sum of the dices from latest roll.
     * @return int The sum of the latest dice roll.
     */
    public function getSum(): int
    {
        return $this->sum;
    }

    /**
     * Getter to retrieve the generated result.
     * @return string The generated game result.
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Resets the round result counter.
     * @return void
     */
    public function resetRounds(): void
    {
        if (isset($_SESSION["player"])) {
            unset($_SESSION["player"]);
        }
        if (isset($_SESSION["computer"])) {
            unset($_SESSION["computer"]);
        }
    }

    /**
     * Saves the result of the played round to the session.
     * @return void
     */
    public function saveRound(string $winner): void
    {
        if (isset($_SESSION[$winner])) {
            $_SESSION[$winner] = $_SESSION[$winner] + 1;
            return;
        }
        $_SESSION[$winner] = 1;
        return;
    }

    /**
     * Getter for current DiceHand.
     */
    public function getDiceHand()
    {
        return $this->diceHand;
    }

    /**
     * Getter for rollCount.
     */
    public function getRollCount()
    {
        return $this->rollCount;
    }
}
