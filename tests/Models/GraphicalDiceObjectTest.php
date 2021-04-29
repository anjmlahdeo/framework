<?php

namespace Rois\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class GraphicalDiceObjectTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use one argument corresponding to number of sides
     * on dice.
     */
    public function testCreateObjectOneArgument()
    {
        $dice = new GraphicalDice(6);
        $this->assertInstanceOf("\Rois\Dice\GraphicalDice", $dice);
    }

    /**
     * Test that the Graphical dice produces a correct graphic string
     * based on its value.
     */
    public function testDiceReturnsGraphic()
    {
        $dice = new GraphicalDice(6);
        $dice->setDiceValue(4);
        $expected = "dice-4";
        $graphic = $dice->graphic();
        $this->assertEquals($expected, $graphic);
    }
}
