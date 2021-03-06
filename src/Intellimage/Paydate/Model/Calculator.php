<?php

namespace Intellimage\Paydate\Model;

use Intellimage\Paydate\Exception\InvalidInput;
use DateTime;
use DateInterval;

class Calculator
{
    /**
     * date from where to calculate
     * @var string
     */
    private $_from = null;
    
    /**
     * amount of months to calculate
     * @var int
     */
    private $_amount = null;
    
    /**
     * date format used for payment dates
     * @var string
     */
    private $_dateFormat = 'Ymd';
    
    /**
     * month format
     * @var string
     */
    private $_monthFormat = 'F Y';
    
    /**
     * sets the from date from where calculate the paydates
     * @param string $from format yyyymm
     * @return \Intellimage\Paydate\Model\Calculator
     * @throws InvalidInput
     */
    public function setFromMonth($from)
    {
        if ($from === 'now') {
            $this->_from = date('Ym');
            return $this;
        }
        
        $date = DateTime::createFromFormat('Ym', $from);
        if ($date && $date->format('Ym') === $from) {
            $this->_from = $from;
        } else {
            throw new InvalidInput("Invalid provided month: " . $from);
        }
        
        return $this;
    }
    
    /**
     * returns the from date set
     * @return string
     */
    public function getFromMonth()
    {
        return $this->_from;
    }
    
    /**
     * sets the amount of months that will be calculated
     * @param string $amount format yyyymm
     * @return \Intellimage\Paydate\Model\Calculator
     * @throws InvalidInput
     */
    
    public function setAmount($amount)
    {
        
        if (!preg_match('(^[0-9]+$)', $amount) || intval($amount) <= 0) {
            throw new InvalidInput("Invalid amount provided: " . $amount);
        }
        
        $this->_amount = intval($amount);
        return $this;
    }
    
    /**
     * returns the amount set
     * @return int
     */
    public function getAmount()
    {
        return $this->_amount;
    }
    
    /**
     * Sets the date format used for payment dates
     * @param string $format
     * @return \Intellimage\Paydate\Model\Calculator
     */
    public function setDateFormat($format)
    {
        $this->_dateFormat = $format;
        return $this;
    }
    
    /**
     * Sets the month format
     * @param string $format
     * @return \Intellimage\Paymonth\Model\Calculator
     */
    public function setMonthFormat($format)
    {
        $this->_monthFormat = $format;
        return $this;
    }
    
    /**
     * returns the from date
     * @return DateTime
     */
    private function _getFromDateTime()
    {
        return DateTime::createFromFormat('Ym', $this->_from);
    }
    
    /**
     * returns the dates for the payment dates for each calculated month
     * @return \Intellimage\Paydate\Model\Calculator
     * @return array of dates
     */
    public function calculate()
    {
        if (!$this->_amount || !$this->_from) {
            throw new InvalidInput ("Please setup amount and from date");
        }
        
        $from = $this->_getFromDateTime();
        $amount = $this->_amount;
        $return = array();
        
        while ($amount--) {
            
            $name = $from->format($this->_monthFormat);
            
            $from = DateTime::createFromFormat('Ymd', $from->format('Ym') . 15);
            
            if ($from->format('N') > 5) {
                $from->add(new DateInterval('P' . (4 - ($from->format('N') - 6)) . 'D'));
            }
            $bonusDate = $from->format($this->_dateFormat);
            
            $from->add(new DateInterval("P1M"));
            $from = DateTime::createFromFormat('Ymd', $from->format('Ym') . 1);
            $from->sub(new DateInterval("P1D"));
            if ($from->format('N') > 5) {
                $from->sub(new DateInterval('P' . ($from->format('N') - 5) . 'D'));
            }
            $salaryDate = $from->format($this->_dateFormat);
            
            $array[] = array(
                $name,
                $salaryDate,
                $bonusDate
            );
            
            $from->sub(new DateInterval("P" . ($from->format('d') - 1) . "D"));
            $from->add(new DateInterval("P1M"));
        }
        
        return $array;
    }
    
}