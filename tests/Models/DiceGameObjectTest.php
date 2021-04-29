<?php

namespace Rois\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceGameObjectTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use one argument corresponding to number of sides
     * on dice.
     */
    public function testCreateObjectOneArgument()
    {
        $game = new Game();
        $this->assertInstanceOf("\Rois\Dice\Game", $game);
    }

    /**
     * Test that playGame function initializes a new DiceHand and rolls
     * the correct number of dices.
     */
    public function testPlayGame()
    {
        $game = new Game();
        $game->playGame(2);
        $dices = $game->getDices();
        $values = $game->getValues();
        $this->assertCount(2, $dices);
        $this->assertCount(2, $values);
    }

    public function testGetDiceHand()
    {
        $game = new Game();
        $game->playGame(2);
        $diceHand = $game->getDiceHand();
        $this->assertInstanceOf("\Rois\Dice\DiceHand", $diceHand);
    }

    /**
     * Test that a new roll can be made and that values change.
     * @runInSeparateProcess
     */
    public function testNewRoll()
    {
        $game = new Game();
        $game->playGame(2);
        $firstValues = $game->getValues();
        $game->newRoll();
        $secondValues = $game->getValues();
        $this->assertNotEquals($firstValues, $secondValues);
    }

    /**
     * Test that a new roll with with ending values produces
     * a result.
     */
    public function testNewRollSetsResult()
    {
        $game = new Game();
        $game->playGame(2);
        $game->newRoll(21);
        $result = $game->getResult();
        $this->assertEquals("You win!", $result);
        $game->newRoll(22);
        $result = $game->getResult();
        $this->assertEquals("You Lost!", $result);
    }

    /**
     * Test that the stop function produces correct results when
     * provided with ending values.
     */
    public function testStopSetsResult()
    {
        $game = new Game();
        $game->playGame(2);
        $game->stop(20, 15);
        $result = $game->getResult();
        $this->assertEquals("You win! Computers score was: 15", $result);
        $game->stop(15, 20);
        $result = $game->getResult();
        $this->assertEquals("You lost! Computers score was: 20", $result);
    }

    /**
     * Test that correct sum is produced.
     */
    public function testGetSum()
    {
        $game = new Game();
        $game->playGame(2);
        $values = $game->getValues();
        $calcSum = array_sum($values);
        $gameSum = $game->getSum();
        $this->assertEquals($calcSum, $gameSum);
    }

    /**
     * Test that $_SESSION is called and that rounds are reset.
     */
    public function testResetRound()
    {
        $_SESSION = [];
        $_SESSION["player"] = 2;
        $_SESSION["computer"] = 3;
        $game = new Game(2);
        $game->resetRounds();
        $this->assertEmpty($_SESSION);
    }
}
