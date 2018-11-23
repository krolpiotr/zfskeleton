<?php
/**
 * @link      ZF3.Prototype.Project
 * @copyright Copyright (c) 2000-2018 The PHOENIX Developer Studio (http://simon-phoenix.se)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

use Application\Factory\IndexControllerFactory; // tcpdf support

return [
    // orginal routes
    //-----------------------------------------------------------------------------
    /*
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/index[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    */
    //-----------------------------------------------------------------------------


    'router' => [
        'routes' => [
            'default' => [
        		'type'    => Segment::class,
        		'options' => [
        			'route'    => '/[:lang[/:controller[/:action]]]',
        			'constraints' => [
        				'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
        				'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',	
        				'lang' => '[a-z]{2}(-[A-Z]{2}){0,1}'
        			],
        			'defaults' => [
        				//'lang'       => 'en-US',
        				'controller' => Controller\IndexController::class,
        				'action'     => 'index',
        			],
        		],
            ],
            'home' => array(
        		'type' => Literal::class,
        		'options' => array(
        			'route'    => '/',
        			'defaults' => array(
        				//'lang'       => 'en-US',
        				'controller' => Controller\IndexController::class,
        				'action'     => 'index',
        			),
        		),
            ),
		    'about' => array(
		        'type' => Segment::class,
		        'options' => array(
		            'route'    => '/[:lang/]index/about',
		            'constraints' => array(
		                'lang'   => '[a-zA-Z0-9_-]*',
		                //  'moms'     => '[0-9]+',
		            ),
		            'defaults' => array(
		                //'lang'       => 'en-US',
		                'controller' => Controller\IndexController::class,
		                'action'     => 'about',
		            ),
		        ),
            ),
		    'pdfsupport' => array(
		        'type' => Segment::class,
		        'options' => array(
		            'route'    => '/[:lang/]index/pdfsupport',
		            'constraints' => array(
		                'lang'   => '[a-zA-Z0-9_-]*',
		                //  'moms'     => '[0-9]+',
		            ),
		            'defaults' => array(
		                //'lang'       => 'en-US',
		                'controller' => Controller\IndexController::class,
		                'action'     => 'pdfsupport',
		            ),
		        ),
            ),
            'language' => array(
                'type' => Segment::class,
                'options' => array(
                        'route'    => '/[:lang/]language',
                        'defaults' => array(
                                //'lang'       => 'en-US',
                                'controller' => Controller\IndexController::class,
                                'action'     => 'language',
                        ),
                ),
        ),
        ],

    ],       




    




    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class, // orginal
            //Controller\IndexController::class => IndexControllerFactory::class, // pdf support
        ],
    ],
    
    /*
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    */
    
'view_helpers' => [
    'invokables' => [
        'translate' => \Zend\I18n\View\Helper\Translate::class
    ]
], 

'service_manager' => [
    'factories' => [
        \Zend\I18n\Translator\TranslatorInterface::class => \Zend\I18n\Translator\TranslatorServiceFactory::class,
    ],
         //  'aliases' => [
         //   'translator' => 'MvcTranslator',
         //  ],

           //'services' => array(
           // 'session' => new Zend\Session\Container('prototype'),
           //),
],

'session_containers' => [
    'ContainerNamespace'
],

// https://github.com/ZF-Commons/ZfcUser/issues/519
       'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
            array(
                'type'     => 'gettext',
                'base_dir' => './vendor/zf-commons/zfc-user/src/ZfcUser/language',
                'pattern'  => '%s.mo',
            ),
        ),

    ), 

    'session' => array(
        'remember_me_seconds' => 30,                       // old 24192000
        'use_cookies' => true,
        'cookie_httponly' => true,
        'gc_maxlifetime' => 30, //60 * 60 * 24 * 30*3,     // old 24192000
        'cookie_lifetime' => 30,                           // old 24192000
    ),
/*
    'translatorzfcuser' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => './vendor/zf-commons/zfc-user/src/ZfcUser/language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),  */
    /*
    'service_manager' => [
        'aliases' => [
            'translator' => 'MvcTranslator',
        ],
    ], */
    /*
'translate' => [
    'locale' => 'en_US',
    'translation_file_patterns' => [
        [
            'type' => 'gettext',
            'base_dir' => APPLICATION_MODULE_ROOT . '/language',
            'pattern' => '%s.mo',
        ],
    ],
],
    */
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
