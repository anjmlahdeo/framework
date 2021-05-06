<?php

namespace Rois\Yatzy;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class YatzyGameObjectTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use one argument corresponding to maximum number
     * of throws allowed.
     */
    public function testCreateObjectOneArgument()
    {
        $game = new YatzyGame();
        $this->assertInstanceOf("\Rois\Yatzy\YatzyGame", $game);
    }

    /**
     * Test that game can be initialized and that it produces a new
     * Round object.
     */
    public function testInitYatzyGame()
    {
        $game = new YatzyGame();
        $game->initGame();
        $round = $game->getCurrentRound();
        $this->assertInstanceOf("\Rois\Yatzy\Round", $round);
    }

    /**
     * Test that a new Round object is created and that
     * the round count is incremented.
     */
    public function testNewRound()
    {
        $game = new YatzyGame();
        $game->newRound();
        $currentRound = $game->getCurrentRound();
        $this->assertInstanceOf("\Rois\Yatzy\Round", $currentRound);
        $roundCount = $game->getRoundCount();
        $this->assertEquals(2, $roundCount);
    }

    /**
     * Test that current round can be saved with the specific
     * target supplied.
     */
    public function testSaveRound()
    {
        $game = new YatzyGame();
        $game->initGame();
        $currentRound = $game->getCurrentRound();
        $diceHand = $currentRound->getDiceHand();
        $diceHand->roll();
        $valueToSave = $diceHand->values()[1];
        $currentRound->storeDices(1);

        $game->saveRound($valueToSave);
        $totalScore = $game->getTotalScore();
        $this->assertEquals($valueToSave, $totalScore);
    }

    /**
     * Test that saveRound ends game if roundCount is equal to 6.
    */
    public function testSaveRoundEndGame()
    {
        $game = new YatzyGame();
        $game->initGame();
        $currentRound = $game->getCurrentRound();
        $diceHand = $currentRound->getDiceHand();
        $diceHand->roll();
        $game->saveRound(2, 6);
        $end = $game->checkEndGame();
        $this->assertTrue($end);
    }

    /**
    * Test that all getRounds returns an array of correct size.
    */
    public function testRoundRetrieval()
    {
        $game = new YatzyGame();
        $rounds = $game->getRounds();
        $this->assertIsArray($rounds);
        $this->assertCount(6, $rounds);
    }
}
