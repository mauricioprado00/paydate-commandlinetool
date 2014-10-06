<?php

$global = ' ';
$global_after = ' [--to-file=] [--delimiter=] [--enclose=] [--date-format=] [--month-format=]';

return array(
    'Paydate' => array(
        'disable_usage' => false,    // set to true to disable showing available ZFTool commands in Console.
    ),

    // -----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=

    'controllers' => array(
        'invokables' => array(
            'Paydate_Create'   => 'Intellimage\Paydate\Controller\CreateController',
            'Paydate_Calculate'   => 'Intellimage\Paydate\Controller\CalculateController',
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'paydate-calculate-next-months-from' => array(
                    'options' => array(
                        'route'    => 'calculate next <months> from <from>' . $global_after,
                        'defaults' => array(
                            'controller' => 'Paydate_Calculate',
                            'action'     => 'calculate',
                        ),
                    ),
                ),
                'paydate-calculate-next-months' => array(
                    'options' => array(
                        'route'    => 'calculate next <months>' . $global_after,
                        'defaults' => array(
                            'controller' => 'Paydate_Calculate',
                            'action'     => 'calculate',
                        ),
                    ),
                ),
                'paydate-calculate' => array(
                    'options' => array(
                        'route'    => 'calculate' . $global_after,
                        'defaults' => array(
                            'controller' => 'Paydate_Calculate',
                            'action'     => 'calculate',
                        ),
                    ),
                ),
                'paydate-calculate-from' => array(
                    'options' => array(
                        'route'    => 'calculate from <from>' . $global_after,
                        'defaults' => array(
                            'controller' => 'Paydate_Calculate',
                            'action'     => 'calculate',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
