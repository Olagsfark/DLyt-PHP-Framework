<?php
startScript();

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */

/** 
 * This is the script responsible for Managing plugins
 */

final class DMainPlugin{ 
    
    static function loadList(){
        $mainFile = 'main.plugin'; //i should force the developer to use main.plugin.php by default
        $includes = array();
        foreach (DSettings::$pluginList as $key => $val){
            if($val['active'])
                $includes[] = DFPath('plugins.'.$key,$mainFile);
        }
        foreach ($includes as $k)
            include_once $k;
    }
    public static function isPluginLoaded($pluginName){
        return DSettings::$pluginList[$pluginName]['active'];
    }
    /**
     * Returns an array containing the plugin information
     * @param string $pluginName
     */
    public static function getPluginInfo($pluginName){
        return DSettings::$pluginList[$pluginName];
    }
    public static function activatePlugin($pluginName){
        DSettings::$pluginList[$pluginName]['active'] = true;
    }
    public static function deactivatePlugin($pluginName){
        DSettings::$pluginList[$pluginName]['active'] = false;
    }
}

DMainPlugin::loadList();

?>
