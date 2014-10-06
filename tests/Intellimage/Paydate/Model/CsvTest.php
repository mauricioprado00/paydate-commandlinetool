<?php

namespace Test\Intellimage\Paydate\Model;

use \Intellimage\Paydate\Exception\InvalidInput;
use \Intellimage\Paydate\Model\Csv;

class CsvTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param array $methods
     * @return \Intellimage\Paydate\Model\Csv
     */
    public function mockCsv($methods = null)
    {
        return $this->getMock("\Intellimage\Paydate\Model\Csv", $methods);
    }

    public function testSetInvalidHeaders()
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        
        $mock = $this->mockCsv();
        $mock->setHeaders("Month,Salary Payment Date,Bonus Payment Date,Another Invalid Header");
    }

    public function testSetInvalidDelimiter()
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        
        $mock = $this->mockCsv();
        $mock->setDelimiter(",;");
    }

    public function testSetInvalidEnclose()
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        
        $mock = $this->mockCsv();
        $mock->setDelimiter("'`");
    }
    

    public function testValidInitialization()
    {
        $mock = $this->mockCsv();
        $mock->setHeaders("Month,Salary Payment Date,Bonus Payment Date")
            ->setDelimiter(',')
            ->setEnclose('"');
    }
    
    public function testExportUninitialized()
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        
        $mock = $this->mockCsv();
        $mock->export(array());
    }
    
    public function testExportUninitialized2()
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        
        $mock = $this->mockCsv();
        $mock->setDelimiter('"')
            ->setHeaders("Month,Salary Payment Date,Bonus Payment Date")
            ->export(array());
    }
    
    public function testExportUninitialized3()
    {
        $this->setExpectedException('\Intellimage\Paydate\Exception\InvalidInput');
        
        $mock = $this->mockCsv();
        $mock->setDelimiter('"')
            ->setEnclose('"')
            ->export(array());
    }
   
}