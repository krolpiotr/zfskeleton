<?php
/**
 * @link      ZF3.Prototype.Project
 * @copyright Copyright (c) 2000-2018 The PHOENIX Developer Studio (http://simon-phoenix.se)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;
//---------------------------------------------------------------------------------
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\Validator;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Locale;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;

use Zend\Session\Config\StandardConfig;

use Zend\I18n\Translator\Resources;
use Zend\I18n\Translator\Translator;


//---------------------------------------------------------------------------------
class Module
{
    const VERSION = '3.0.3-dev';

    public $sessionContainer;

    
    public function onBootstrap(MvcEvent $e)
    {
		//$eventManager        = $e->getApplication()->getEventManager();
        $application         = $e->getApplication();
		$services            = $application->getServiceManager();
		$config              = $services->get('Config');
		$translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');

		$phpSettings = isset($config['phpSettings']) ? $config['phpSettings'] : array();
    	if(!empty($phpSettings)) {
    		foreach($phpSettings as $key => $value) {
    			ini_set($key, $value);
    		}
		} 
		
    	$locale = 'en_US';
		$default = 'en_US';
		
        //$session = new SessionManager();
        
		$viewModel  = $e->getViewModel();
		$viewModel->some_config_var = '12345';
		//$viewModel->route_locale;
		
    	// configuration from module.config.php
    	//-------------------------------------------------------------------------
        $sessionConfig = new SessionConfig();
		/*
        $config_cc = new StandardConfig();
        $config_cc->setOptions([
            'remember_me_seconds' => 30,
            'name'                => 'zf3',
        ]);
		$sessionManager = new SessionManager($config_cc);
		*/
		$sessionConfig->setOptions($config['session']);
    	$session = new SessionManager($sessionConfig);
    	$session->start();
        $this->sessionContainer = new Container('ContainerNamespace', $session);
    	//-------------------------------------------------------------------------
    	// default lang from browser
    	$translator->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    	->setFallbackLocale('en_US');
    	$browser_locale = $translator->getLocale();
    	$br_locale = str_replace('_', '-', $browser_locale);    	
			  
		//-------------------------------------------------------------------------
        // if we don't have lang in session
    	if (!isset($session->locale)) {
    		 
    		$services = $e->getApplication()->getServiceManager();
    		$config = $e->getApplication()->getServiceManager()->get('Configuration');
    		$localesConfig = $config['locales'];
    		$locales = $localesConfig['list'];
    		/*
    		// unsupported locale provided
    		if (!in_array($br_locale, array_keys($locales))
    		&& $e->getApplication()->getRequest()->getUri()->getPath() !== '/') {
    		
    			$locale = $localesConfig['default'];
    			$url = $e->getRouter()->assemble(array(
    					'locale' => $localesConfig['default']
    			), array('name' => 'home'));
    			$response = $e->getApplication()->getResponse();
    			$response->getHeaders()->addHeaderLine('Location', $url);
    			$response->setStatusCode(Response::STATUS_CODE_302);
    			$response->sendHeaders();
    			exit;
    		}   
            */
    		if (!in_array($br_locale, array_keys($locales))){
    			$locale = $localesConfig['default'];
    			$cor_locale_5 = str_replace('-', '_', $locale);
    			
    			$session->locale = $locale;
    			
    			$viewModel->lang_sv_SE = $cor_locale_5;
				$viewModel->lang_svSE = $locale; 

				$viewModel->route_locale  = $locale;;			
    		}
    		// If there is no locale parameter in the route, switch to default locale
    		if (empty($locale)) {
    			$locale = $localesConfig['default'];
    		}    		
    		 
    		// jesli tak to go od razu ustawiamy jako domyslny
    		if (in_array($br_locale, array_keys($locales))) {
    			// wysylamy wszystkie przydatne nam informacjie
    			$session->locale = $br_locale;
    			$locale = $browser_locale;
    			
    			//$viewModel->lang_sv = $locale;
    			$viewModel->lang_sv_SE = $browser_locale;
    			$viewModel->lang_svSE = $br_locale;    			
    			
    		}
    	} // if (isset($session->locale)) {    	
    	    	
    	// kiedy wartosc sesji jest dostepna    // ZMIENILEMMMMMMMMMMMMMMMMMM
    	//-------------------------------------------------------------------------
    	if (isset($this->sessionContainer->locale)) {
    		$cor_locale = str_replace('-', '_', $this->sessionContainer->locale);
    		$locale = $cor_locale;
			
			
           // echo var_dump($this->sessionContainer->locale." sesja dostepna");

    		$viewModel->lang_sv_SE = $cor_locale;
			$viewModel->lang_svSE = $session->locale;   
			
			$viewModel->route_locale  = $this->sessionContainer->locale;
    	}
    	$translator->setLocale($locale);
    	\Locale::setDefault($locale);
    	//-------------------------------------------------------------------------
    	
    	//var_dump(' LANGUAGE IN SESSION: '.$cor_locale.','.' LOCALE: '.$locale.','.' YOUR BROWSER LANGUAGE: '.$br_locale.'');    	
    	
    	// parametr
    	//-------------------------------------------------------------------------
    	$eventManager = $e->getApplication()->getEventManager();
    	$eventManager->attach('route', function ($e) {
    		$services = $e->getApplication()->getServiceManager();
    		$config = $e->getApplication()->getServiceManager()->get('Config');
    		$localesConfig = $config['locales'];
    		$locales = $localesConfig['list'];
    		$locale = $e->getRouteMatch()->getParam('lang');  // zmienic na locale
    		//$loc_temp = str_replace('-', '_', $locale);
    	
    		$viewModel  = $e->getViewModel();
            //$session = $services->get('session');   TU ZROBIC ZEBY DZIALALO!!!!!!!!!!!!!!!!!!!!!
            $session = $this->sessionContainer;
    		    	
    		
    		//$viewModel->lang_svSE = $locale;
    		
    		// If there is no lang parameter in the route, nothing to do
    		if (empty($locale)) {
    			return;
    		}
    		 
    		$services = $e->getApplication()->getServiceManager();
    		 
    		// If the session language is the same, nothing to do
            //$session = $services->get('session');     // ZMIENILEMMMMMMMMMMMM
            //$session = $this->sessionContainer;
    		if (isset($session->locale) && ($session->locale == $locale)) {
    			return;
    		}
    		
    		if (!in_array($locale, array_keys($locales))){
    			$locale = $localesConfig['default'];
    			$cor_locale_5 = str_replace('-', '_', $locale);
    			$session->locale = $locale;
    		
    			$viewModel->lang_sv_SE = $cor_locale_5;
				$viewModel->lang_svSE = $locale;
				
				$viewModel->route_locale  = $locale;
    		}
    		 
    		$session->locale = $locale;
    		
    		$loc_temp = str_replace('-', '_', $locale);
    		
    		$translator = $services->get('MvcTranslator');
    		$translator->setLocale($loc_temp);
    		\Locale::setDefault($loc_temp); 
    		 
    		//$viewModel  = $e->getViewModel();
    		//$viewModel->lang_sv = $locale;
    		$viewModel->lang_sv_SE = $loc_temp;
			$viewModel->lang_svSE = $locale;   


			$viewModel->route_locale  = $locale;		
    	
    		//var_dump(' LANGUAGE IN SESSION: '.$session->locale.','.' LOCALE: '.$locale.','.' LOCALE TEMP: '.$loc_temp.'');
    		
    	}, -10); // $eventManager->attach('route', function ($e) {    	
    	//-------------------------------------------------------------------------
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->bootstrapSession($e);
        
    }
    protected function forgetInvalidSession($sessionManager) 
    {
    	try {
    		$sessionManager->start();
    		return;
    	} catch (\Exception $e) {
    	}
    	/**
    	 * Session validation failed: toast it and carry on.
    	 */
    	// @codeCoverageIgnoreStart
    	session_unset();
    	// @codeCoverageIgnoreEnd
    }
    //-----------------------------------------------------------------------------
    public function bootstrapSession($e)
    {
        /*$session = $e->getApplication()
            ->getServiceManager()
            ->get(SessionManager::class);
        $session->start();*/
        
        $session = new SessionManager();
        $session->start();

        $container = new Container('initialized');

        if (isset($container->init)) {
            return;
        }

        $serviceManager = $e->getApplication()->getServiceManager();
        $request        = $serviceManager->get('Request');

        $session->regenerateId(true);
        $container->init          = 1;
        $container->remoteAddr    = $request->getServer()->get('REMOTE_ADDR');
        $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');

        $config = $serviceManager->get('Config');
        if (! isset($config['session'])) {
            return;
        }

        $sessionConfig = $config['session'];

        if (! isset($sessionConfig['validators'])) {
            return;
        }

        $chain   = $session->getValidatorChain();

        foreach ($sessionConfig['validators'] as $validator) {
            switch ($validator) {
            case Validator\HttpUserAgent::class:
                    $validator = new $validator($container->httpUserAgent);
                    break;
                    case Validator\RemoteAddr::class:
                    $validator  = new $validator($container->remoteAddr);
                    break;
                default:
                    $validator = new $validator();
            }

            $chain->attach('session.validate', array($validator, 'isValid'));
        }
        echo var_dump($session. "hghg");
    }
	//-----------------------------------------------------------------------------
    public function getServiceConfig()
    {
        return [
            'factories' => [
                SessionManager::class => function ($container) {
                    $config = $container->get('config');
                    if (! isset($config['session'])) {
                        $sessionManager = new SessionManager();
                        Container::setDefaultManager($sessionManager);
                        return $sessionManager;
                    }

                    $session = $config['session'];

                    $sessionConfig = null;
                    if (isset($session['config'])) {
                        $class = isset($session['config']['class'])
                            ?  $session['config']['class']
                            : SessionConfig::class;

                        $options = isset($session['config']['options'])
                            ?  $session['config']['options']
                            : [];

                        $sessionConfig = new $class();
                        $sessionConfig->setOptions($options);
                    }

                    $sessionStorage = null;
                    if (isset($session['storage'])) {
                        $class = $session['storage'];
                        $sessionStorage = new $class();
                    }

                    $sessionSaveHandler = null;
                    if (isset($session['save_handler'])) {
                        // class should be fetched from service manager
                        // since it will require constructor arguments
                        $sessionSaveHandler = $container->get($session['save_handler']);
                    }

                    $sessionManager = new SessionManager(
                        $sessionConfig,
                        $sessionStorage,
                        $sessionSaveHandler
                    );

                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },
            ],
        ];
    }
    //-----------------------------------------------------------------------------
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
