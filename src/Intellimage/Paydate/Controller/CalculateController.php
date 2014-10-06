<?php

namespace Intellimage\Paydate\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\ConsoleModel;
use ZFTool\Model\Skeleton;
use ZFTool\Model\Utility;
use Zend\Console\ColorInterface as Color;
use Zend\Code\Generator;
use Zend\Code\Reflection;
use Intellimage\Paydate\Model\Calculator;
use Intellimage\Paydate\Model\Csv;
use Intellimage\Paydate\Exception\InvalidInput;

class CalculateController extends AbstractActionController
{
    /**
     * default from date if not provided
     */
    const DEFAULT_FROM = 'now';
    
    /**
     * default amount of months if not provided
     */
    const DEFAULT_MONTHS = 12;
    
    /**
     * default csv delimiter character if not provided
     */
    const CSV_DELIMITER = ',';
    
    /**
     * default csv enclose character if not provided
     */
    const CSV_ENCLOSE = '"';
    
    /**
     * default csv headers if not provided
     */
    const CSV_HEADERS = 'Month,Salary Payment Date,Bonus Payment Date';
    
    /**
     * default date format used for payment dates if none provided
     */
    const DEFAULT_DATE_FORMAT = 'Ymd';
    
    public function calculateAction()
    {
        $request = $this->getRequest();
        $console = $this->getServiceLocator()->get('console');
        $parameters = $request->getParams();
        $file = $parameters->get('to-file', '');
        
        try {
            $calculator = new Calculator();

            $calculator->setFromMonth($parameters->get("from", self::DEFAULT_FROM))
                ->setAmount($parameters->get('months', self::DEFAULT_MONTHS))
                ->setDateFormat($parameters->get('date-format', self::DEFAULT_DATE_FORMAT));
            
            $csv = new Csv();
            $csv->setDelimiter($parameters->get('delimiter', self::CSV_DELIMITER))
                ->setEnclose($parameters->get('enclose', self::CSV_ENCLOSE))
                ->setHeaders($parameters->get('headers', self::CSV_HEADERS));
            if ($file) {
                $csv->setFile($file);
            }
            $csv->export($calculator->calculate());
            
        } catch(InvalidInput $e) {
            return $this->sendError($e->getMessage());
        }

        if ($file) {
            $console->writeLine("Written file $file", Color::GREEN);
        } else {
            echo $csv->getContent();
        }
    }

    /**
     * Send an error message to the console
     *
     * @param  string $msg
     * @return ConsoleModel
     */
    protected function sendError($msg)
    {
        $console = $this->getServiceLocator()->get('console');
        $m = new ConsoleModel();
        $m->setErrorLevel(2);
        $m->setResult($msg . PHP_EOL);
        
        return $m;
    }
}
