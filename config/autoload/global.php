<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Zend\Session;

return [
    'session_manager' => [
        'config' => [
            'class' => Session\Config\SessionConfig::class,
            'options' => [
                'name' => 'prototype',
            ],
        ],
        'storage' => Session\Storage\SessionArrayStorage::class,
        'validators' => [
            Session\Validator\RemoteAddr::class,
            Session\Validator\HttpUserAgent::class,
        ],
    ],
    
    'locales' => [
        'default' => 'en-US',
        'list' => [
            'en-US' => 'English',
         //   'pl-PL' => 'Polski',
			'sv-SE' => 'Svenska',
        ],
    ],
];
