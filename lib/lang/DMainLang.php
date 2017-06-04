<?php

DStartScript();

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */

/**
 * main language file
 * contains mostly constants, e.t.c
 */
final class DMainLang {

    static $errorConsts = array(
        'test' => 'Some Error uhn?',
        'UnxpectedObject' => 'An unexpected object has been recieved.',
        'DTaskInterval' => 'Task type has to be <i>D_TASK_TYPE_REPEAT</i> for you to set an interval',
        'DTaskType' => 'You can only set a task type as <i>D_TASK_TYPE_REPEAT</i> or <i>D_TASK_TYPE_ONCE</i>',
        'templateBadHook' => 'No matchable template block for %s was found',
        'templateNoHook' => 'Template context has no hooks!',
        'sessionNotSafe' => 'Sorry, your session authentication data have been corrupted. Please restart your browser.',
        'websiteTitleError' => '<b>$globalName</b> config has to be greater than 3 and less than 20 in size',
        'routerInitError' => 'You have to set atleast one app in the <b>$router</b> config'
    );
    static $templates = array(
        'errorOutput' => "<div style='color:#9C2B0E;background-color:#FDECE8;padding:5px;' title='Dlyt PHP Framework Error Output'>%s</div>",
        'taskManagerData' => 'name=%s,interval=%d,execTimes=%d,task=%s,nextTime=%d',
        'errorLogFormat' => '  %s in %s at <%s>  ' . PHP_EOL
    );

}

function DStartScript() {
    defined('DLYTFRAMEWORKPHP') or die('Cannot process request!!');
}
function DLoadScript($path, $include = true) {
    //package = 'lib.util' e.t.c ...if path = *. load everything inside
    //I just feel this guy would be better as a function.
    //Its easier to call that way since its meant to replace a prominent php function
    //i had to move this function here;
    return $include ? include_once $path . '.php' : $path . '.php';
}


////////constants
define('D_HTTP_URL', '1');
define('D_PAGE_RESTRICTED', '2');
define('D_PAGE_OPEN', '3');
define('D_AUTOLOAD_DEFAULT', '4');
define('D_AUTOLOAD_HANDLER', '5');
define('D_AUTOLOAD_VIEW', '6');
define('D_AUTOLOAD_MODEL', '7');
define('D_AUTOLOAD_APP', D_AUTOLOAD_HANDLER | D_AUTOLOAD_MODEL); //this should only be called from the view class
define('D_VIEW_USE_TEMPLATE', '8');
define('D_VIEW_USE_DEFAULT', '9');
define('D_REQUEST_TYPE_IPV4', '10');
define('D_REQUEST_TYPE_IPV6', '11');
define('D_REQUEST_NO_IP', '12');
define('D_REQUEST_NO_USERAGENT', '13');
define('D_TASK_TYPE_REPEAT', '14');
define('D_TASK_TYPE_ONCE', '15');
?>