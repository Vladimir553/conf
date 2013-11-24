<?
$module_name = 'conf';
global $DB;
$res = $DB->Query('SELECT * FROM `b_option` WHERE `MODULE_ID` = "' . $module_name . '"');
$options = array();
while ($option = $res->Fetch())
    $options[] = $option['NAME'];

$tabControl = new CAdminTabControl("tabControl", 
                array(
                    array("DIV" => "edit",
                          "TAB" => 'Редактирование настроек', 
                          "TITLE" => 'Редактирование настроек')
                    ,
                   array("DIV" => "create",
                          "TAB" => 'Добавление настроек', 
                          "TITLE" => 'Добавление настроек')
                    )
                );

if ((strlen($_POST['Update'] . $_POST['Apply']) > 0) && check_bitrix_sessid()) {
    foreach ($options as $optionName){
        COption::SetOptionString($module_name, $optionName, $_POST['conf'][$optionName]);
    }
    $_POST['new_conf_name'] = array_filter($_POST['new_conf_name']);
    if (count($_POST['new_conf_name'])) {
        for ($i = 0; $i < count($_POST['new_conf_name']); $i++) {
            COption::SetOptionString($module_name, $_POST['new_conf_name'][$i], $_POST['new_option_val'][$i]);
            $options[] = $_POST['new_conf_name'][$i];
        }
    }

    if (strlen($_REQUEST['Update']) && strlen($_REQUEST['back_url_settings'])) {
        LocalRedirect($_REQUEST['back_url_settings']);
    } else {
        LocalRedirect($GLOBALS['APPLICATION']->GetCurPage() . "?mid=" . urlencode($mid) . "&lang=" . (LANGUAGE_ID) . "&back_url_settings=" . urlencode($_REQUEST["back_url_settings"]));
    }
}
$tabControl->Begin(); 
?>
<form  method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>"> <?
$tabControl->BeginNextTab();
if($options){
    foreach ($options as $optionName) {
        ?><tr><td width="50%"><?= $optionName ?></td>
            <td width="50%"><input type="text" size="43" value="<?= COption::GetOptionString($module_name, $optionName) ?>" name="conf[<?= $optionName ?>]" /></td>
        </tr>
    <?
    }
} else {
    ?>
        <p>Отсутствуют записи. Для добавления переключитесь на вторую вкладку<></p>
        <?
}
$tabControl->BeginNextTab();
for ($j = 1; $j <= 10; $j++) {
    ?> 
    <tr>
        <td width="50%">
            <input type="text" size="25" placeholder="Имя настройки" name="new_conf_name[]" />
        </td>
           <td width="50%"> <input type="text"  size="43" placeholder="Значение" name="new_option_val[]" />
        </td>
    </tr>
<? } ?>        
<?php $tabControl->Buttons() ?>
<input type="submit" name="Update" value="Сохранить"  title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" />
<input type="submit" name="Apply" value="Применить" title="Применить" /><?=bitrix_sessid_post();?>
<?php $tabControl->End() ?>
</form>
<?=BeginNote()?>
<p>Получить установленное значение можно так: <? 
echo str_replace('&lt;?', '', highlight_string('<? $val = COption::GetOptionString(\'conf\', \'имя настройки\');', true));?></p><?
EndNote();