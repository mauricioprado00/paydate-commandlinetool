<?php

namespace Intellimage\Paydate\Model;

use Intellimage\Paydate\Exception\InvalidInput;
use DateTime;

class Calculator
{
    
    private $_from = null;
    
    private $_amount = null;
    
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
            return true;
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
        
        if (!preg_match('(^[0-9]+$)', $amount)) {
            throw new InvalidInput("Invalid amount provided: " . $amount);
        }
        
        $this->_amount = $amount;
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
}