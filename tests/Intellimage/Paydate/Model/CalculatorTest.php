<?php

namespace Test\Intellimage\Paydate\Model;

use \Intellimage\Paydate\Exception\InvalidInput;
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
    
    public function testRunUninitializedFrom()
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        $mock = $this->mockCalculator();
        $mock->setAmount(1);
        
        $mock->calculate();
    }
    
    public function testRunUninitializedAmount()
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        $mock = $this->mockCalculator();
        $mock->setFromMonth('201412');
        
        $mock->calculate();
    }
    
    /**
     * @dataProvider amountOfReturnedRowsProvider
     */
    public function testAmountOfReturnedRows($amount)
    {
        $mock = $this->mockCalculator();
        $mock->setFromMonth('201412')
            ->setAmount($amount);
        
        $result = $mock->calculate();
        
        $this->assertCount($amount, $result);
    }
    
    public function amountOfReturnedRowsProvider()
    {
        $params = array();
        
        for ($i = 1; $i < 100; $i++) {
            $params[] = array($i);
        }
        
        return $params;
    }
    
    /**
     * @dataProvider testMonthNamesProvider
     */
    public function testMonthNames($month, $monthNames)
    {
        $mock = $this->mockCalculator();
        $mock->setFromMonth($month)
            ->setAmount(count($monthNames));
        
        $result = $mock->calculate();
        
        foreach ($result as $idx => $row) {
            $this->assertEquals($monthNames[$idx], $row[0]);
        }
        
    }
    
    public function testMonthNamesProvider()
    {
        return array(
            array('201401', array('January 2014', 'February 2014', 'March 2014', 'April 2014')),
            array('201405', array('May 2014', 'June 2014', 'July 2014', 'August 2014')),
        );
    }
    
    /**
     * @dataProvider testBonusDatesProvider
     */
    public function testBonusDates($month, $dates)
    {
        $mock = $this->mockCalculator();
        $mock->setFromMonth($month)
            ->setAmount(count($dates));
        
        $result = $mock->calculate();
        
        foreach ($result as $idx => $row) {
            $this->assertEquals($dates[$idx], $row[2]);
        }
        
    }
    
    public function testBonusDatesProvider()
    {
        return array(
            array('201401', array('20140115', '20140219', '20140319', '20140415')),
            array('201405', array('20140515', '20140618', '20140715', '20140815')),
        );
    }
    
    /**
     * @dataProvider testSalaryDatesProvider
     */
    public function testSalaryDates($month, $dates)
    {
        $mock = $this->mockCalculator();
        $mock->setFromMonth($month)
            ->setAmount(count($dates));
        
        $result = $mock->calculate();
        
        foreach ($result as $idx => $row) {
            $this->assertEquals($dates[$idx], $row[1]);
        }
        
    }
    
    public function testSalaryDatesProvider()
    {
        return array(
            array('201401', array('20140131', '20140228', '20140331', '20140430')),
            array('201405', array('20140530', '20140630', '20140731', '20140829')),
        );
    }
    
}