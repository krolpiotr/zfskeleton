<?php
namespace ZendSkeletonModule;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\SkeletonController::class => InvokableFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'module-name-here' => [
                'type'    => Literal::class,
                'options' => [
                    // Change this to something specific to your module
                    'route'    => '/module-specific-root',
                    'defaults' => [
                        'controller'    => Controller\SkeletonController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // You can place additional routes that match under the
                    // route defined above here.
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'ZendSkeletonModule' => __DIR__ . '/../view',
        ],
    ],
];
