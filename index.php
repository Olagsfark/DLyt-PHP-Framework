<?php

/**
 * Dlyt PHP Framework
 * 
 * <b>Framework Philosophy</b>
 *   <p>This framework uses the conventional MVC system of application development;
 *   but the MVC framework philosophy used in this framework has been customized a bit
 *   to a more natural language.</p> 
 *   <li>- Model View Controller(MVC) - <tt>Conventional title</tt></li>
 *   <li>- Model View Handler(MVH)    - <tt>Framework version of the title</tt></li>
 *   <p>To clarify issues, the MVH notation does not change anything from the 
 *   MVC philosophy. The framework follows all conventional MVC practices.</p>
 * 
 * <b>Technical Ideology</b>
 *   <p>The core features and inspirations of the framework are:</p>
 *   <li>- Speed,</li> 
 *   <li>- Efficiency,</li> 
 *   <li>- Security,</li> 
 *   <li>- Extreme simplicity, </li> 
 *   <li>- Extensibility and versatility;</li> 
 *   <p>hence anyone making commits and collaborators should please bear these in mind.</p>
 * 
 * <b>Basic Idea</b>
 *    <p>The basic idea is to have a framework that wont create more API jargon, nor 
 *    introduce new ways to code(which is usually for sake of being fancy);
 *    The idea is to have a framework that manages the security, authentication, 
 *    database management, and backend code organisation & optimization <i>WITHOUT</i> 
 *    necessarily depriving the programmer of his good perfect ways of coding but 
 *    instead provide the most basic to most advanced programmers with a huge in built 
 *    and third-party collection of useful tools and utilities, and provide these tools in such 
 *    a way that, you dont have to unlearn anything nor do you have to learn new APIs. The 
 *    programmer is just requred to know the tool; and USE IT!! just like that.</p>
 *
 *    <p>Another main point is to have a framework that is easy to start and setup. 
 *    Dlyt PHP Framework is being built independent codebase that is void of any sort of 
 *    external dependencies as it ships readily. </p>
 * 
 * <b>Please Note: The Framework is being built with >= PHP 5.3 currently.</b>
 * 
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 * @version 2.1.0
 * @license https://github.com/Olagsfark/Dlyt-PHP-Framework/blob/master/LICENSE GNU GENERAL PUBLIC LICENSE
 * @link http://olagsfark.github.io/Dlyt-PHP-Framework Visit framework github repository for full info and tutorials
 * 
 */
/*
 * Setting the framework name into the __NAME__ constant
 */
define('__NAME__', "Dlyt PHP Framework");
/*
 * Setting the framework version number into the __VERSION__ constant
 */
define('__VERSION__', '2.2.0');
/*
 * Setting the framework author into the __AUTHOR__ constant
 */
define('__AUTHOR__', 'Olagoke Adedamola Farouq');

/**
 * @link https://github.com/Olagsfark/Dlyt-PHP-Framework/wiki For "Getting Started" tutorials please visit
 */
/**
 * we need to define this constant in order to hold all scripts together
 * Also for script security
 */
define('DLYTFRAMEWORKPHP', 'etaoinshrdlu');

//make sure they are on version 5.3 or higher
if (version_compare(PHP_VERSION, '5.3.10', '<'))
    die('Your host needs to use PHP 5.3.10 or higher to run this version of Dlyt Framework');

/**
 * We need all errors to be registered as the framework has an error buffer handling mechanism
 * @see DCache Link to the error buffer handling documentation
 */
error_reporting(defined('E_STRICT') ? E_ALL | E_STRICT : E_ALL);

/**
 * We define a constant to ensure we have a level of cross platform safety
 */
define('SLASH', DIRECTORY_SEPARATOR);

/**
 * The DMainLang.php contains some framework constant which are all used by the framework internally
 * The knowledge about the workings of this file isnt necessary.
 * 
 * @see DMainLang Documentation on the DMainLang class and its containing script
 */
include_once('DSettings.php');
DSettings::init();

include_once('DMainLang.php');


/**
 * This class contains core functionalities of the framework
 * The containing script contains the DFPath() function
 * 
 * @link http://not-ready See the Documentation on the DlytCore class
 * @link http://not-ready See how the DFPath() helps with path management
 * @see loadScript()
 */
DLoadScript('DlytCore');

/**
 * Now loading the framework's internal core scripts and preparing environment
 * 
 */
DlytCore::init();
if (DSettings::$sessionConfig['active'] === true)
    DSession::start();
/**
 * Invoking the DBufferHandler(if its been enabled)
 * Its important to start the BufferHandler before any output
 * 
 * @link http://not-ready See the Documentation on the DBufferHandler class
 * @link http://not-ready See the Documentation on the DSettings class
 */
if (DSettings::$enablePlugins)
    if (DMainPlugin::isPluginLoaded('cacheManager'))
        cacheManager::startBuffer();

/**
 * If there has been some errors before, we cant start
 */
if (DErrorHandler::hasError())
    DErrorHandler::showAll();
else
    new main; //off we go...
/**
 * Closing the DBufferHandler(if its been enabled)
 * 
 * @link http://not-ready See the Documentation on the DBufferHandler class
 * @link http://not-ready See the Documentation on the DSettings class
 */
if (DSettings::$enablePlugins)
    if (DMainPlugin::isPluginLoaded('cacheManager'))
        cacheManager::endBuffer();

/**
 * <p>The main class can be likened to the main function in java and c++, this class 
 * serves as the entry point for the framework. </p>
 * <p>The class is responsible for initiating needed classes and functionalities before passing 
 * the control to the requested app through the app's view component</p>
 */
final class main {

    function __construct() {
        $this->init();
        $this->connect();
    }

    /**
     * The connect() method is the one that actually processes the request and passes control to the 
     * requested app's view component. Its my replacement for the initially intended DRouter class. 
     * I think its just simpler this way
     */
    private function connect() {
        $get = DRequest::GET('action'); 
        //if they dont ask for an action, show them the index page
        $page = $get !== false ? $get : DSettings::$router['default_apps']['index'];
        if (isset(DSettings::$router['apps'][$page])) {
            if (DSettings::$router['apps'][$page]['access'] == 'restricted')
                DLoadScript(DSettings::$router['apps'][DSettings::$router['default_apps']['error_403']]['path']);
            else
                DLoadScript(DSettings::$router['apps'][$page]['path']);
        } else {
            DLoadScript(DSettings::$router['apps'][DSettings::$router['default_apps']['error_404']]['path']);
        }
    }

    /**
     * setting the framework's error handler. and many more that will be added later
     */
    private function init() {
        set_error_handler('_DBTSRSetErrors'); // setting the error handler
        DRequest::init(); //the request handler module
    }

    /**
     * Finishing touches...
     * its important to show the Buffer of errors as all the errors are logged away and can only be 
     * explicitly accessed.
     * @see DErrorHandler
     */
    function __destruct() {
        //its important to show the Buffer of errors as all the errors are logged away and can only be 
        // explicitly accessed
        //have a method that handles buffering out of the index.php,
        DErrorHandler::showAll();
    }

}

?>
