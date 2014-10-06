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
            array('[--quote=]', 'if not specified double quote (") is used.'),
            
            'Examples', 
            
            'Calculate next 12 months paydates:',
            'calculate'     => 'generates next 12 months paydates from not to the console output',
            
            'Calculate next 12 months paydates to file:',
            'calculate --to-file="/tmp/next_paydates.csv"'     => 'generates next 12 months paydates from not to the console output',
            
            'Calculate next 24 months paydates:',
            'calculate next 24'     => 'generates next 24 months paydates from now to the console output',
            
            'Calculate next 24 months paydates from May of 2015:',
            'calculate next 24 from 201505'     => 'generates next 24 months paydates from May 2015 to the console output',
            
            
        );
    }
}


$x = array(
            'Global options:', 
            array('--noautoload|-n', 'By default every model will be try to be resolved. So the package_module dont have to be specified.' . PHP_EOL . 
                'You can force to disable this behaviour with this noautoload flag.' . PHP_EOL .
                'You cannot ommit package_module if the target directory has more than one module or package'),
            'Parameter types:',
            array('[classname]', ' All attributes of type  must be in package_module/model format.'),
            array('[cammelcase]', ' A cammelcase format parameter. ie: someParameter.'),
            array('[dashed]', ' A dasshed format parameter. ie: some_parameter.'),
            array('[string]', 'A quote enclosed parameter. ie: "some parameter"'),
            
            'Module creation:',
            'create module <name> [<path>]'     => 'create a module',
            array('<name>', 'The name of the module to be created'),
            array('<path>', 'The root path of a ZF2 application where to create the module'),
            
            'Model creation:',
            'create varien_model <model_name>'   => 'create a varien model',
            array('<model_name>', $model_name = '[classname]'),

            'create model <model_name>[:<extended_model_name>] [..resource model options..]'   => 'create model',
            array('<model_name>', $model_name),
            array('<extended_model_name>', '[classname] The model to be extended. If ommited then Mage_Core_Model_Abstract will be extended.'),
            array('..resource model options..', 'The name of the resource model to link with. If it does not exist can be created adding the resource model options'),

            'create resource-model [--pk=entity_id] [-e] <resource_name>[:<extended_resource_name>] [..entity options..]'   => 'create a varien model',
            array('<resource_name>', $model_name),
            array('<extended_resource_name>', '[classname] The resource to be extended. If ommited then Intellimage_Supplier_Model_Mysql4_Supplier will be extended.'),
            array('[--eav|-e]', 'use this flag if you pretend to create an eav resource model'),
            array('[--pk=entity_id]', $primary_key = 'use this parameter to specify an alternative primary key.'),
            array('[--collection]', "Use this flag to create a collection for the resource."),
            array('..table options..', 'The name of the resource model to link with. If it does not exist can be created adding the resource model options'),
            
            'create collection <collection_name> [<resource_name>]'   => 'create a resource collection',
            array('<collection_name>', $collection_name = '[classname] must not have the _collection classname extension'),
            array('<resource_name>', '[classname] name of the resource used will be the same.'),

            'add collection filter <attribute_code> [<filter_value> <filter_name>]'   => 'create a resource collection filter',
            array('<collection_name>', $collection_name = '[classname] must not have the _collection classname extension'),
            array('<attribute_code>', '[dashed] code of the attribute'),
            array('<filter_value>', '[string] If ommited then a boolean type value is assumed and two methods will be created: ' . PHP_EOL .
                'add[AttributeName]FilterEnabled and add[AttributeName]FilterDisabled.'),
            array('<filter_name>', '[cammelcase] Name of the filter. generated: add[NameOfTheFilter]Filter'),

            'add entity [--pk=entity_id] <entity_name>[/<table_name>]'   => 'create a varien model',
            array('<entity_name>', '[dashed] The name of the entity'),
            array('<table_name>', '[dashed] The name of the table, if not ommited then entity_name will be used.'),
            array('[--pk=entity_id]', $primary_key),
            array('..table options..', 'The name of the resource model to link with. If it does not exist can be created adding the resource model options'),
            
            'add  attribute [--eav|-e] [--sqlfieldoptions=options] [--pk] <entity_name> <attribute_code> <attribute_type>'   => 'create an eav attribute',
            array('<entity_name>', '[dashed] The name of the entity'),
            array('<attribute_code>', '[dashed] code of the attribute'),
            array('<attribute_type>', 'int, varchar, text, datetime, decimal'),
            array('[--eav|-e]', 'use this flag if you pretend to create an eav attribute'),
            array('[--sqlfieldoptions=options]', 'use this flag to specify aditional sql options to append to the attribute creation'),
            

            'Controller creation',
            'create controller [-b] [<controller_name>]' => 'create a controller',
            array('<controller_name>', $controller_name = '[classname] Must not have the _controller classname extension. If ommited index will be used. ' . 
                    'Must be in the format package_module/controllerName.' . PHP_EOL .'ie: mymodule_myPackage/index'),
            array('--backend|-b', $controller_backend = 'If the controller it\'s a backend controller.'),
            
            'create action [<controller_name>/]<action_name>' => 'create a controller action',
            array('<controller_name>', $controller_name),
            array('--backend', $controller_backend),
            
            'Examples',
            array("Command","result"),
            array("sdf","d")
            
        );
