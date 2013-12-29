<?
class conf extends CModule {
    
    var $MODULE_ID = 'conf';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS;
    var $errors = array();
 
    function __construct() {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = 'Настройки сайта';
        $this->MODULE_DESCRIPTION = '';
    }

    public function DoInstall() {
        RegisterModule($this->MODULE_ID);
    }
    
    public function DoUninstall(){
        UnRegisterModule($this->MODULE_ID);
    }
    
}