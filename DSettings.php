<?php
defined('DLYTFRAMEWORKPHP') or die('Cannot process request!!');

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */

/**
 * This class contains configuration settings.
 * The framework's configuration is designed in a way that, if a feature(class) is not 
 * configured to be needed in the settings, the containing script would not even get loaded.
 */
final class DSettings {

    /**
     *
     * @var string This config contains the name of the whole website. More like a title
     * this string must not be more than 30 characters long and atleast 3 characters
     */
    static $globalName = 'Dlyt Php Framework';

    /**
     * @var boolean This configuration controls whether database systems are needed and should be loaded or not
     */
    static $databaseConfig = array(
        'active' => false,
        'dbConnectionInfo' => array(
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'database' => 'southernwells'
        ),
        'dbDrivers' => array(
            'DMYSQLDatabase' => true
        //can add other database driver scripts names here
        )
    );

    /**
     * @var boolean This configuration controls whether cookies are needed and should be loaded or not
     */
    static $enableCookies = false;
    
    static $timeConfig = array(
        'defaultTimeZone' => '' //deafult time zone
    );

    /**
     * @var boolean This configuration controls whether sessions are needed and should be loaded or not
     */
    static $sessionConfig = array(
        'active' => true,
        'isHttpOnly' => false,
        'sessionName' => 'southernwells_limited',
        'HTTPSSecure' => true
    );

    /**
     * @var boolean This configuration controls whether plugins are needed and should be loaded or not
     */
    static $enablePlugins = false;

    /**
     * @var boolean This configuration controls whether VPN and users with changing IP addresses are allowed or not
     */
    static $allowDynamicIP = false;

    /**
     *
     * @var array This contains the list of all path you may wana include in the path environ
     */
    static $includePaths = array(
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\lib',
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\lib\abstract',
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\lib\io',
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\lib\lang',
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\lib\util',
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\app\library',
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\app\handler',
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\app\model',
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\app\view',
        'C:\xampp\htdocs\DLyt-PHP-Framework-2\app\view\templates'
        );

    /**
     * @var mixed This configuration should be set to the path of the error log file if you want all errors to be logged 
     * into a file; else should be set to false
     * <pre>
     * static $logErrorsToFile = DFPath('res', 'log.txt', false); 
     * </pre>
     * @see DFPath Know more about the DFPath function
     */
    static $logErrorsToFile = false; //'res/log.txt'; //set to false or the path to the log file
    /**
     * @var array This configuration contains the encryption schemes you want the framework to use <br/>
     * - core is the hash you want the framework's internal core to use
     * - app is the hash you want app components to use by default
     * Please note that all of these schemes must be one way encryption
     */
    static $ecryptionScheme = array(//the encryption schemes to use
        'core' => 'sha1', // this for the framework's core
        'app' => 'sha1'  // this for other use
    );

    /**
     * @var array This configuration contains the listing of pages/apps. 
     *  The config is an array that contains another array. <br/>
     * <b>This is how it works: the 'apps' index</b></br>
     * When You request http://site.com?action='app', the 'app' is the action that 
     * triggers an app being requested; each app must have its route registered as an array 
     * in the <b><tt>$router</tt></b> configuration. And each array must contain the <b>path</b> 
     * component which sets the view script of the app to trigger; and the <b>action</b> 
     * component which sets the keyword with which you request the app from url.
     * <br/>
     * You can set multiple routes for your apps.
     * <hr/>
     * <b>This is how it works: the 'default_apps' index</b></br>
     *   This contains default pages for the case whereby an app requested wasnt found..
     * <br/>For Example:<br/>
     * <tt> - Assuming the apps listing config contained no apps action called 'about' and the action has been requested, then the 
     * framework assumes the error handling page is an action component with the name contained in the <b>'error_404'</b> index.<br/>
     * - Assuming the http request was like <b>http://site.com</b> where no action has been specified, this means that the index resource 
     * of the site is being requested, the framework then loads an action component with the name contained in the <b>'index'</b> index.
     * </tt><br/>
     * Please note that the app actions listed in the 'default_apps' values must be valid actions 
     * and must be properly registered in the $router config too.
     * 
     */
    static $router = array(
        'apps' => array(
            'home' => array(
                'path' => 'home.view',
                'access' => 'open' 
            ),
            'about' => array(
                'path' => 'about.view',
                'access' => 'open'
            ),
            'services' => array(
                'path' => 'services.view',
                'access' => 'open'
            ),
            'portfolio' => array(
                'path' => 'portfolio.view',
                'access' => 'open'
            ),
            'career' => array(
                'path' => 'career.view',
                'access' => 'open'
            ),
            'test' => array(
                'path' => 'test.view',
                'access' => 'open'
            ),
            'error' => array(
                'path' => 'error.view',
                'access' => 'open'
            )
        ),
        'default_apps' => array(
            'index' => 'home', //key = already registered page action
            'error_404' => 'error',
            'error_403' => 'error'
        )
    );

    /**
     * Holds path information; a more elegant option is to use the DFPath() function
     * @var array 
     */
    static $paths = array(
        'httpUrl' => '', //This refers to the localhost location or your equivalent local server http(s) url
        'baseDirectory' => '',
        'resources' => 'res/',
        'siteRootFolder' => 'genysis' //the name of the site folder. very important!
    );

    /**
     * This configuration contains directives for the plugin system. The config contains a list 
     * of objects that depending on either active or not, loads the plugin each object points to.
     * @var array contains the directives for the plugins
     */
    static $pluginList = array(
        'taskManager' => array(//the index here has to bear the same name as the plugin's containing folder
            'title' => 'Tasks Manager Plugin',
            'pluginVersion' => '1.0', //version of the plugin
            'frameworkVersion' => '1.1', //target compatible framework version
            'active' => false
        ),
        'cacheManager' => array(
            'title' => 'Cache Manager Plugin',
            'pluginVersion' => '1.0',
            'frameworkVersion' => '1.1',
            'active' => false
        ),
        'tingle' => array(
            'title' => 'Lightweight templating Plugin',
            'pluginVersion' => '1.0',
            'frameworkVersion' => '1.1',
            'active' => false
        )
    );

    public static function init() {
        if (!empty(self::$includePaths))
            set_include_path(implode(';', self::$includePaths));
        
        self::$paths['baseDirectory'] = dirname(__FILE__) . SLASH;
        
        self::$paths['httpUrl'] = filter_input(INPUT_SERVER, 'REQUEST_SCHEME') . '://' //dirty env data!! security breach!!!
                .filter_input(INPUT_SERVER, 'HTTP_HOST') . '/'
                .self::$paths['siteRootFolder'].'/';
    }

}

