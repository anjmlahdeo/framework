<?php

namespace Rois\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceObjectTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use one argument corresponding to number of sides
     * on dice.
     */
    public function testCreateObjectOneArgument()
    {
        $dice = new Dice(6);
        $this->assertInstanceOf("\Rois\Dice\Dice", $dice);
    }

    /**
     * Test that rolls a dice and then retrives and asserts that the
     * number generated is between 1 and 6 inclusive.
     */
    public function testRollDiceAndGetLastRoll()
    {
        $dice = new Dice(6);
        $dice->roll();
        $result = $dice->getLastRoll();
        $this->assertTrue(1 <= $result && 6 >= $result);
    }

    /**
     * Test that a specific dice value can be set.
     */
    public function testSetDiceValue()
    {
        $dice = new Dice(6);
        $dice->setDiceValue(4);
        $result = $dice->getLastRoll();
        $this->assertTrue($result === 4);
    }
}
