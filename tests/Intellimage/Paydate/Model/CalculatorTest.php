<?php

namespace Test\Intellimage\Paydate\Model;

use Intellimage\Paydate\Exception\InvalidInput;
use \Intellimage\Paydate\Model\Calculator;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param array $methods
     * @return \Intellimage\Paydate\Model\Calculator
     */
    public function mockCalculator($methods = null)
    {
        return $this->getMock("\Intellimage\Paydate\Model\Calculator", $methods);
    }

    /**
     * @dataProvider invalidMonthsProvider
     */
    public function testSetInvalidMonth($month)
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        
        $mock = $this->mockCalculator();
        $mock->setFromMonth($month);
    }
    
    public function invalidMonthsProvider()
    {
        return array(
            array('201413'),
            array('20141201'),
            array('2014/12'),
            array('2014-12'),
            array('2014 12'),
            array('2014 december'),
        );
    }

    /**
     * test that the values are set
     * @dataProvider validMonthsProvider
     */
    public function testSetValidMonth($month, $expected = null)
    {
        $mock = $this->mockCalculator();
        $mock->setFromMonth($month);
        
        $expected = isset($expected) ? $expected : $month;
        
        $this->assertEquals($expected, $mock->getFromMonth(), "The from month set should be the same as the provided");
    }
    
    public function validMonthsProvider()
    {
        return array(
            array('201412'),
            array('201512'),
            array('196901'),
            array('now', date('Ym'))
        );
    }
    
    /**
     * tests that invalid amounts cant be set
     * @param int $amount
     * @dataProvider invalidAmountProvider
     */
    public function testSetInvalidAmount($amount)
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        
        $mock = $this->mockCalculator();
        $mock->setAmount($amount);
    }
    
    public function invalidAmountProvider()
    {
        return array(
            array(-1),
            array(0),
            array("one"),
            array("1.5"),
        );
    }
    
    /**
     * tests that valid amounts cant be set
     * @param int $amount
     * @dataProvider validAmountProvider
     */
    public function testSetValidAmount($amount)
    {
        $mock = $this->mockCalculator();
        $mock->setAmount($amount);
        
        $this->assertEquals($amount, $mock->getAmount(), "The amount set should be the same as the provided");
    }
    
    public function validAmountProvider()
    {
        return array(
            array(1),
            array("1"),
            array(5000),
        );
    }
    
}