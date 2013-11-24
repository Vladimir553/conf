<?
class conf extends CModule{
    public $MODULE_ID           = __CLASS__;
    public $MODULE_VERSION      = '1.0';
    public $MODULE_VERSION_DATE = '2013.11.23';
    public $MODULE_NAME         = 'Настройки сайта';
    public $MODULE_DESCRIPTION  = 'Модуль для редактирвоания собственных настроек через админку';

    public function DoInstall() {
        RegisterModule($this->MODULE_ID);
    }
    
    public function DoUninstall(){
        UnRegisterModule($this->MODULE_ID);
    }
}