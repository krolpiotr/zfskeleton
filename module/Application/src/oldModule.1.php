<?php
/**
 * @link      ZF3.Prototype.Project
 * @copyright Copyright (c) 2000-2018 The PHOENIX Developer Studio (http://simon-phoenix.se)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application111;
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

//---------------------------------------------------------------------------------
class Module
{
    const VERSION = '3.0.3-dev';
    public $locale = 'en_US';
    public $default = 'en_US';
    public $services;
    public $session;
    public $viewModel;
    public $translator;
    

    public $przyklad2;

    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $application         = $e->getApplication();
        $this->services      = $application->getServiceManager();
        $this->session             = new SessionManager();
        $this->viewModel           = $e->getViewModel();
        //-------------------------------------------------------------------------   	
    	// if is no lang in link
    	//-------------------------------------------------------------------------    	    	   	
    	$this->translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');
        $this->translator->setLocale($this->locale);        
    	//-------------------------------------------------------------------------
    	// default lang from browser
    	$this->translator->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    	->setFallbackLocale('en_US');
    	$browser_locale = $this->translator->getLocale();
        $br_locale = str_replace('_', '-', $browser_locale);



$this->przyklad = "pipka";
$this->przyklad2 = "pipkagggggggggggggggggggggggggggggggggggggggggggggg";
        //$session->locale = "sv_SE";


        //echo var_dump($this->services->get("Config"));

        //-------------------------------------------------------------------------
        // if we don't have lang in session
        if (!isset($this->session->locale)) {
            $services = $this->services;
            $config = $services->get("Config");
    		$localesConfig = $config['locales'];
            $locales = $localesConfig['list'];

    		if (!in_array($br_locale, array_keys($locales))){
    			$this->locale = $localesConfig['default'];
    			$cor_locale_5 = str_replace('-', '_', $this->locale);
    			
    			$session->locale = $locale;
    			
    			$this->viewModel->lang_sv_SE = $cor_locale_5;
    			$this->viewModel->lang_svSE = $locale;    			
            }
            
            // If there is no locale parameter in the route, switch to default locale
    		if (empty($this->locale)) {
    			$this->locale = $localesConfig['default'];
    		} 

            // jesli tak to go od razu ustawiamy jako domyslny
    		if (in_array($br_locale, array_keys($locales))) {
    			// wysylamy wszystkie przydatne nam informacjie
    			$this->session->locale = $br_locale;
    			$this->locale = $browser_locale;
    			
    			//$viewModel->lang_sv = $locale;
    			$this->viewModel->lang_sv_SE = $browser_locale;
    			$this->viewModel->lang_svSE = $br_locale;    			
    			
    		}



echo "kkkkkkkkkkkkkkkkkkk";
//$this->session->locale = "sv_SE";
        }
        echo var_dump(' session1 '.$this->session->locale.' session1 ');
        //-------------------------------------------------------------------------
        // if we have lang in session
    	if (isset($this->session->locale)) {
            // jest sesja
            echo "jest sesja ";
    		$cor_locale = str_replace('-', '_', $this->session->locale);
            $this->locale = $cor_locale;
            $this->session->locale = $this->locale;
    		
    		$this->viewModel->lang_sv_SE = $cor_locale;
            $this->viewModel->lang_svSE = $this->session->locale;    		
    	}
    	$this->translator->setLocale($this->locale);
        \Locale::setDefault($this->locale);
        //-------------------------------------------------------------------------
        // Check the route if is parameter
        $eventManager->attach('route', function ($e) {
            // get param first
            $param = $e->getRouteMatch()->getParam('lang');

    		// If there is no lang parameter in the route, nothing to do
    		if (empty($param)) {
    			return;
            }

    		// If the session language is the same, nothing to do
    		if (isset($this->session->locale) && ($this->session->locale == $param)) {
    			return;
            }

            // services and config
            $services = $this->services;
            $config = $services->get("Config");
    		$localesConfig = $config['locales'];
            $locales = $localesConfig['list'];

            // Just for in case if param is no in config
            if (!in_array($param, array_keys($locales))){
    			$localetmp = $localesConfig['default'];
    			$cor_locale_5 = str_replace('-', '_', $localetmp);
    			$this->session->locale = $cor_locale_5;
    		
    			$this->viewModel->lang_sv_SE = $cor_locale_5;
                $this->viewModel->lang_svSE = $locale;
                return;
            }

    		$loc_temp = str_replace('-', '_', $param);
    		
    		//$translator = $services->get('translator');
    		$this->translator->setLocale($loc_temp);
    		\Locale::setDefault($loc_temp); 
            $this->session->locale = $loc_temp;

    		// If the session language is the same, nothing to do
    		//if (isset($this->session->locale) && ($this->session->locale == $locale)) {
    		//	return;
            //}


            //$locale = $e->getRouteMatch()->getParam('lang');


            //$param = $e->getRouteMatch()->getParam('lang');

            echo var_dump(' param '.$param.' param '); 

            
    		// If there is no lang parameter in the route, nothing to do
    		//if (empty($param)) {
    		//	return;
            //}
            
    		// If the session language is the same, nothing to do
    		//if (isset($this->session->locale) && ($this->session->locale == $locale)) {
    		//	return;
            //}
            
            // Just for in case
            //if (!in_array($this->locale, array_keys($locales))){
    		//	$locale = $localesConfig['default'];
    		//	$cor_locale_5 = str_replace('-', '_', $locale);
    		//	$this->session->locale = $this->locale;
    		
    		//	$this->viewModel->lang_sv_SE = $cor_locale_5;
    		//	$this->viewModel->lang_svSE = $locale;
            //}
            // lang_const in view
    		//$loc_temp = str_replace('-', '_', $locale);
    		
    		//$translator = $services->get('translator');
    		//$this->translator->setLocale($loc_temp);
    		//\Locale::setDefault($loc_temp); 
    		 
    		//$viewModel  = $e->getViewModel();
    		//$viewModel->lang_sv = $locale;
    		$this->viewModel->lang_sv_SE = $loc_temp;
    		//$this->viewModel->lang_svSE = $locale; 



//echo var_dump($locales);
//echo var_dump($locale);

            // potrzebne 
            //global $przyklad2;

            echo $this->przyklad2;
echo "oooooo ROUTEoooooo kurwa";

        }, -10); // $eventManager->attach('route', function ($e) {
        //-------------------------------------------------------------------------
        echo var_dump(' session '.$this->session->locale.' session ');

        //$session->locale = $locale;


        
        


        // The following line instantiates the SessionManager and automatically
        // makes the SessionManager the 'default' one.
        //$sessionManager = $serviceManager->get(SessionManager::class);
        //$sessionManager = $event->getApplication()->getServiceManager()->get(SessionManager::class);
       // $this->forgetInvalidSession($sessionManager);
       //$session = new SessionManager();
       //echo var_dump($session);


    	/*
    	$cip = $translator = $e->getApplication()->getServiceManager();
    	
    	$translator = $cip->get('MvcTranslator');
    		$translator->setLocale($locale);
    		\Locale::setDefault($locale); 
        */
        echo var_dump($br_locale);
        
        
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->bootstrapSession($e);
        
        
        //echo var_dump($_SESSION);
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
