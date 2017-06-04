<?php

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 * @package DlytCore
 */
DStartScript();

/**
 * This class holds major framework utility modules that are used mostly for 
 * the framework's core workings.
 */
final class DlytCore {

    /**
     * ----------------------
     * <b>This method autoloads the necessary scripts needed as directed</b><br/>
     *      the method recieves 5 different autoload constant directives in in its <i>$flag</i> parameter
     * <br/>
     *       <b>1. <tt>D_AUTOLOAD_APP</tt></b>
     *       <ul>
     *          <li>
     *              Passing this constant along with the hook causes a model and handler class of the hook to be created.
     *          </li>
     *          <li>
     *              The constant can only be used in the app's view class as it only loads the model and handler components
     *          </li>
     *      </ul>
     *      <tt>For example: <b> DlytCore::autoloadScripts(D_AUTOLOAD_APP, 'home'); </b><br/>
     *          if this statement is written in a view component script, <br/>
     *          - home.model.php is first loaded<br/>
     *          - then home.handler.php is loaded finally<br/>
     *      </tt>
     * <br/>
     *       <b>2. <tt>D_AUTOLOAD_HANDLER</tt></b>
     *       <ul>
     *          <li>
     *              Passing this constant along with the hook causes a handler class of the hook to be created.
     *          </li>
     *          <li>
     *              The constant may be used in any component where the handler component is needed.
     *          </li>
     *      </ul>
     *      <tt>For example: <b> DlytCore::autoloadScripts(D_AUTOLOAD_HANDLER, 'home'); </b><br/>
     *          if this statement is written in any component script, <br/>
     *          - home.handler.php is loaded<br/>
     *      </tt>
     * <br/>
     *       <b>3. <tt>D_AUTOLOAD_MODEL</tt></b>
     *       <ul>
     *          <li>
     *              Passing this constant along with the hook causes a model class of the hook to be created.
     *          </li>
     *          <li>
     *              The constant may be used in any component where the model component is needed.
     *          </li>
     *      </ul>
     *      <tt>For example: <b> DlytCore::autoloadScripts(D_AUTOLOAD_MODEL, 'home'); </b>
     *          if this statement is written in any component script, <br/>
     *          - home.model.php is loaded<br/>
     *      </tt>
     * 
     * @param constant $flag Can contain either of the D_AUTOLOAD_* variant constants
     * @param string $hook the name of the hook you wana use to create the component parts
     */
    public static function autoloadScripts($flag, $hook = NULL) {

        if ($flag == D_AUTOLOAD_DEFAULT) {
            self::addCoreScripts();
            self::addPluginScripts();
        } elseif ($flag == D_AUTOLOAD_APP && $hook != NULL) {
            //can oly be used in the view class to load the handler and model of the view name
            self::addAppScripts($hook);
        } elseif ($flag == D_AUTOLOAD_HANDLER && $hook != NULL) {
            //loads the handler and its abstract
            self::$scriptList[] = DLoadScript('DHandler', false);
            self::$scriptList[] = DLoadScript($hook . '.handler', false);
        } elseif ($flag == D_AUTOLOAD_MODEL && $hook != NULL) {
            //loads the model and its abstract
            self::$scriptList[] = DLoadScript('DTemplate', false);
            self::$scriptList[] = DLoadScript('DModel', false);
            self::$scriptList[] = DLoadScript($hook . '.model', false);
        } elseif ($flag == D_AUTOLOAD_VIEW && $hook != NULL) {
            //load the view's abstract
            self::$scriptList[] = DLoadScript('DVIew', false);
        }

        foreach (self::$scriptList as $k) //the scripts are included in top to down order
            include_once $k;
    }

    public static function init() {
        self::autoloadScripts(D_AUTOLOAD_DEFAULT);
        DUtils::init();
        self::secureEnvConfig(); //seems kinda not cool kinda
    }

    private static function secureEnvConfig() { //lots of checking to do here
        //check session config
        //check db, plugin, encryption
        //make sure default apps are all registered
        $titleCheck = (!isset(DSettings::$globalName[2]) || isset(DSettings::$globalName[29]));
        $routerCheck = empty(DSettings::$router['apps']) || empty(DSettings::$router['apps']);
        if ($titleCheck)
            trigger_error(DMainLang::$errorConsts['websiteTitleError']);
        elseif ($routerCheck)
            trigger_error(DMainLang::$errorConsts['routerInitError']);
    }

    private static function addCoreScripts() {
        self::$scriptList[] = DLoadScript('DSecurity', false);
        self::$scriptList[] = DLoadScript('DRequest', false);
        if (DSettings::$sessionConfig['active'] === true)
            self::$scriptList[] = DLoadScript('DSession', false);
        self::$scriptList[] = DLoadScript('DUtils', false);
        self::$scriptList[] = DLoadScript('DFile', false);
        self::$scriptList[] = DLoadScript('DErrorHandler', false);
        self::$scriptList[] = DLoadScript('DLytException', false);
        //self::$scriptList[] = DLoadScript('DBenchmark', false);
        if (DSettings::$databaseConfig['active'] === true)
            foreach (DSettings::$databaseConfig['dbDrivers'] as $k => $v)
                if ($v === true)
                    self::$scriptList[] = DLoadScript($k, false);
    }

    private static function addPluginScripts() {
        if (DSettings::$enablePlugins)
            self::$scriptList[] = DLoadScript('DMainPlugin', false);
    }

    private static function addAppScripts($hook) {
        self::$scriptList[] = DLoadScript('DHandler', false);
        self::$scriptList[] = DLoadScript($hook . '.handler', false);
        self::$scriptList[] = DLoadScript('DModel', false);
        self::$scriptList[] = DLoadScript($hook . '.model', false);
        self::$scriptList[] = DLoadScript('DView', false);
    }
    

    private static $scriptList = array();

}

////////////////
/*
 * Some bootstrap functions
 * The _DBTSR suffixed functions are not meant to be used by the programmer, they are internal workers
 */

/**
 * By default, all errors are logged through here. Hence to see any error; we have to expicilty request for it
 * This function helps to do the real error logging into the DErrorHandler class.
 * @see DErrorHandler
 */
function _DBTSRSetErrors($error, $errorString, $filename, $line, $symbols) {
    //check to make sure error isnt fatal
    $tag = DArray::getInstance(explode(SLASH, $filename))->lastValue() . " ($line) ";
    DErrorHandler::addError("$tag : <b>$errorString</b>", $tag);
}

/**
 * This function just prints out an error string merged with an already made template
 * @param type $error_msg The error message to be printed out
 */
function _DBTSRTriggerError($error_msg) {
    printf(DMainLang::$templates['errorOutput'], $error_msg);
}

/**
 * This function helps to form url strings by recieving the url action.
 * @param string $action the app action to target
 */
function DUrl($target, $package = '') {
    return DSettings::$paths['httpUrl'] . (($target == false || $target == null) ? str_replace('.', '/', $package) . '/' : (($package === null || empty($package)) ? '?action=' : str_replace('.', '/', $package) . '/') . $target);
}

/**
 * An initial thought was to have a whole class to manage paths in the framework; but i managed to collapse everyting into a function; 
 * which gives us more speed!! weee!
 * 
 * the $path string must contain resource locations much like java and python does...kinda cool if you ask me
 * the $path variable contains modularized path strings on which the script will be appended.
 * <br/><tt>E.g 
 * <b>DFPath('res');</b> will produce the complete full path to the res folder in the root folder of the framework<br/>
 * <b>DFPath('app.handler');</b> will produce full path to <b>root_folder/app/handler</b><br/>
 * <b>DFPath('app.handler','test');</b> will produce full path to <b>root_folder/app/handler/test.php</b><br/>
 * <b>DFPath('app.handler','test.html');</b> will produce full path to <b>root_folder/app/handler/test.html.php</b><br/>
 * <b>DFPath('app.handler','test.html',false);</b> will produce full path to <b>root_folder/app/handler/test.html</b><br/>
 * </tt>
 * <p>The $path parameter can also recieve a constant <tt>HTTP_URL</tt> defined in DMainLang, by which the function returns the 
 * http url of the website's root folder.</p>
 * <p>By default, a '.php' extension is always added to the script string unless a false is passed to $useDefaultSuffix; in which case 
 * the script string is left untouched</p>
 * 
 * @param type $path just like java, D and python's package syntaxes, it serves to pass contains modularized path strings on which the script will be appended
 * @param string $script The name of the script or file to load
 * @param boolean $useDefaultSuffix if true, a default '.php' extension is added to the end of the $script string else, the string 
 * is left as is
 * @return string the full path to the requested path
 */
function DFPath($path, $script = '', $useDefaultSuffix = true) {

    $script = ($script != '') ? $script . ($useDefaultSuffix ? '.php' : '') : '';
    return (DSettings::$paths['baseDirectory'] . ($path == '.' ? '' : str_replace('.', SLASH, $path) . SLASH)) . $script;
    //DFrameworkPath
}

/**
 * This function uses the header() to load the reqested registered app by the action
 * @param type $appAction
 */
function DLoadApp($appAction, $data = null) {
    $data = ($data != null) ? '&feedback=' . $data : '';
    header("location: index.php?action=$appAction" . $data);
}

function DLoadLibrary($libraryName, $returnHandle = false){
    include_once DFPath('app.library',$libraryName);
    if($returnHandle)
        return new $libraryName();
}

function DGetViewContext($viewName){
    DStartScript();
    DlytCore::autoloadScripts(D_AUTOLOAD_APP, $viewName); //loads the app's model and handler component
    return getModelContext($viewName)->context;
}