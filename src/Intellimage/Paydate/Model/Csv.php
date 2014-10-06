<?php

namespace Intellimage\Paydate\Model;

use Intellimage\Paydate\Model\Calculator;
use Intellimage\Paydate\Exception\InvalidInput;

class Csv
{
    /**
     * filename to where store the output
     * @var string
     */
    private $_file; 
    
    /**
     * headers for the csv output
     * @var array
     */
    private $_headers = null;
    
    /**
     * enclose character
     * @var string
     */
    private $_enclose = null;
    
    /**
     * deliminter character
     * @var string
     */
    private $_delimiter = null;
    
    const AMOUNT_COLUMNS = 3;
    
    /**
     * 
     * @param string $file
     * @return \Intellimage\Paydate\Model\Calculator
     */
    public function setFile($file)
    {
        $this->_file = $file;
        return $this;
    }
    
    /**
     * set the headers for the csv output
     * @param string $headers a coma separated set of headers
     * @return \Intellimage\Paydate\Model\Calculator
     */
    public function setHeaders($headers)
    {
        $headers = explode(',', $headers);
        if (count($headers) !== self::AMOUNT_COLUMNS) {
            throw new InvalidInput("Invalid amount of headers provided, 3 needed");
        }
        $this->_headers = $headers;
        return $this;
    }
    
    /**
     * sets the delimiter character
     * @param string $delimiter
     * @return \Intellimage\Paydate\Model\Csv
     */
    public function setDelimiter($delimiter)
    {
        if (strlen($delimiter) !== 1) {
            throw new InvalidInput("The delimiter must be a single character, provided value: $delimiter");
        }
        $this->_delimiter = $delimiter;
        return $this;
    }
    
    /**
     * setst the enclose character
     * @param string $enclose
     * @return \Intellimage\Paydate\Model\Csv
     */
    public function setEnclose($enclose)
    {
        if (strlen($enclose) !== 1) {
            throw new InvalidInput("The delimiter must be a single character, provided value: $enclose");
        }
        $this->_enclose = $enclose;
        return $this;
    }
    
    
    public function export($data)
    {
        if (!isset($this->_enclose, $this->_delimiter, $this->_headers)) {
            throw new InvalidInput("Missing parameters");
        }
        
        if (!$this->_file) {
            $this->_file = tempnam('/tmp/', 'paydate_calculation_');
        }

        $handler = fopen($this->_file, 'w');
        
        fputcsv($handler, $this->_headers, $this->_delimiter, $this->_enclose);
        foreach ($data as $d) {
            fputcsv($handler, $d, $this->_delimiter, $this->_enclose);
        }
        fclose($handler);
    }
    
    public function getFile()
    {
        return $this->_file;
    }
    
    /**
     * returns the csv content
     * @return string
     */
    public function getContent()
    {
        if ($this->_file) {
            return file_get_contents($this->_file);
        }
    }
}