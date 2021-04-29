<?php

namespace Rois\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceHandObjectTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use one argument corresponding to number of dices.
     */
    public function testCreateObject()
    {
        $diceHand = new DiceHand(5);
        $this->assertInstanceOf("\Rois\Dice\DiceHand", $diceHand);
    }

    /**
     * Test that dices can be retrieved when object has just been created.
     */
    public function testRetriveDicesOnCreate()
    {
        $diceHand = new DiceHand(5);
        $dices = $diceHand->getDices();
        $this->assertCount(5, $dices);
        $this->isInstanceOf("\Rois\Dice\DiceHand", $diceHand);
        $this->assertInstanceOf("\Rois\Dice\GraphicalDice", $dices[0]);
    }

    /**
     * Test that roll function produces 5 dice values and that they can
     * be retrieved from the object, and that values from 2 rolls are not equal.
     */
    public function testRetrieveValuesOnRoll()
    {
        $diceHand = new DiceHand(5);
        $diceHand->roll();
        $firstValues = $diceHand->values();
        $diceHand->roll();
        $secondValues = $diceHand->values();
        $this->assertCount(5, $firstValues);
        $this->assertCount(5, $secondValues);
        $this->assertIsInt($firstValues[0]);
        $this->assertIsInt($secondValues[3]);
        $this->assertNotEquals($firstValues, $secondValues);
    }

    /**
     * Test that sum of values from object is correct.
     */
    public function testSumValues()
    {
        $diceHand = new DiceHand(5);
        $diceHand->roll();
        $values = $diceHand->values();
        $sumFromObj = $diceHand->sum();
        $calcSum = array_sum($values);
        $this->assertEquals($calcSum, $sumFromObj);
    }

     /**
     * Test that dice can be removed and added.
     */
    public function testAddRemoveDice()
    {
        $diceHand = new DiceHand(5);
        $diceHand->roll();
        //Get initial values
        $dices = $diceHand->getDices();
        $values = $diceHand->values();
        $this->assertCount(5, $dices);
        $this->assertCount(5, $values);

        //Remove one dice
        $diceHand->spliceDice(2);
        $dices = $diceHand->getDices();
        $values = $diceHand->values();
        $this->assertCount(4, $dices);
        $this->assertCount(4, $values);

        //Add one dice
        $diceHand->addDice(5);
        $dices = $diceHand->getDices();
        $values = $diceHand->values();
        $this->assertCount(5, $dices);
        $this->assertCount(5, $values);
    }
}
