<?php
return array(
    'modules' => array(
        'Paydate' => 'Intellimage\\Paydate',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            'Intellimage\\Paydate' => realpath(__DIR__ . '/../src'),
        ),
    ),
);

