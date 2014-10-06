<?php

namespace Intellimage\Paydate;

use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements ConsoleUsageProviderInterface, AutoloaderProviderInterface, ConfigProviderInterface
{
    const NAME    = 'Paydate commandline Tool';
    
    /**
     * @var ServiceLocatorInterface
     */
    protected $sm;

    
    private $_application = null;
    
    private $_config = null;


    public function onBootstrap(EventInterface $e)
    {
        $this->_application = $e->getApplication();
        $this->sm = $this->_application->getServiceManager();
    }

    public function getConfig($configPath = null, $asObjects=false)
    {
        if (!isset($this->_config)) {
            $this->_config = include __DIR__ . '/../../../config/module.config.php';
        }
        $config = $this->_config;
        if (isset($configPath)) {
            $config = $this->_config;
            $path = explode('/', $configPath);
            while (count($path) && isset($config[$path[0]])) {
                $config = $config[array_shift($path)];
            }
            if (count($path)) {
                $config = null;
            }
        }
        
        if ($asObjects) {
            $config = json_decode(json_encode($config));
        }
        return $config;
    }
    
    public function getApplication()
    {
        return $this->_application;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getConsoleBanner(ConsoleAdapterInterface $console)
    {
        return self::NAME;
    }

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        $config = $this->sm->get('config');
        if(!empty($config['Paydate']) && !empty($config['Paydate']['disable_usage'])){
            return null; // usage information has been disabled
        }
        
        return array(
            'calculate [next <amout_of_months>] [from <month>]'     => '',
            
            'General options: ',
            array('<amout_of_months>', 'The number of paydate months you need to generate'),
            array('<month>', 'A month, specified in the format yyyymm (e.g.: 201505, 201412)'),
            
            'Additional parameters:',
            array('[--to-file=]', 'if not specified the csv is delivered in the console stdout. '),
            array('[--delimiter=]', 'if not specified comma (,) is used.'),
            array('[--enclose=]', 'if not specified double quote (") is used.'),
            array('[--date-format=]', 'format used for payment dates. If not specified "Ymd" is used.'),
            array('[--month-format=]', 'format used for the month column. If not specified "F Y" is used.'),
            
            'Examples', 
            
            'Calculate next 12 months paydates:',
            'calculate'     => 'generates next 12 months paydates from not to the console output',
            
            'Calculate next 12 months paydates to file:',
            'calculate --to-file="/tmp/next_paydates.csv"'     => 'generates next 12 months paydates from not to the console output',
            
            'Calculate next 12 months paydates to file with date formats as 2014/05/01:',
            'calculate --date-format="Y/m/d"'     => 'generates next 12 months paydates from not to the console output',
            
            'Calculate next 12 months paydates to file with month formats as 2014/05/01:',
            'calculate --month-format="Y/m/d"'     => 'generates next 12 months paydates from not to the console output',
            
            'Calculate next 24 months paydates:',
            'calculate next 24'     => 'generates next 24 months paydates from now to the console output',
            
            'Calculate next 24 months paydates from May of 2015:',
            'calculate next 24 from 201505'     => 'generates next 24 months paydates from May 2015 to the console output',
            
            
        );
    }
}

