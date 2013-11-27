<?
$module_name = 'conf';
global $DB;
$res = $DB->Query('SELECT * FROM `b_option` WHERE `MODULE_ID` = "' . $module_name . '"');
$options = array();
while ($option = $res->Fetch()){
    $options[] = $option['NAME'];
}
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

if ((strlen($_REQUEST['Apply']) > 0) && check_bitrix_sessid()) {
    foreach ($options as $k => $optionName){
         if (isset($_REQUEST['conf'][$optionName]['delete'])) {
            COption::RemoveOption('conf', $optionName);
            unset($options[$k]);
        } else {
            COption::SetOptionString($module_name, $optionName, $_REQUEST['conf'][$optionName]['val']);
        }
    }
    $_REQUEST['new_conf_name'] = array_filter($_REQUEST['new_conf_name']);
    if (count($_REQUEST['new_conf_name'])) {
        for ($i = 0; $i < count($_REQUEST['new_conf_name']); $i++) {
            COption::SetOptionString($module_name, $_REQUEST['new_conf_name'][$i], $_REQUEST['new_option_val'][$i]);
            $options[] = $_REQUEST['new_conf_name'][$i];
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
if($options){?>
    <tr>
        <td width="30%"><b>Имя</b></td>
        <td width="30%"><b>Значение</b></td> 
        <td width="35%"><b>Удалить</b></td> 
        </tr><?
    foreach ($options as $optionName) { 
        ?><tr> 
          <td width="30%"><?=$optionName?> [<a href='#' onclick='prompt("Скопируйте код и вставьте в нужное место шаблона", "echo COption::GetOptionString(\"conf\",\"<?=$optionName?>\");"); return false;'>код</a>]</td>
          <td width="30%"><input type="text" size="43" value="<?= COption::GetOptionString($module_name, $optionName) ?>" name="conf[<?= $optionName ?>][val]" /></td>
          <td width="35%"><input type="checkbox" name="conf[<?= $optionName ?>][delete]"></td>
        </tr>
    <?
    }
} else {
    ?>
        <p>Отсутствуют записи. Для добавления переключитесь на вкладку 'Добавление настроек'</p>
        <?
}
$tabControl->BeginNextTab();
for ($j = 1; $j <= 6; $j++) { ?> 
    <tr>
      <td width="45%">
        <input type="text" size="25" placeholder="Имя настройки" name="new_conf_name[]" />
      </td>
        <td width="45%"> <input type="text"  size="43" placeholder="Значение" name="new_option_val[]" />
      </td>
      <td width="10%"></td>
    </tr>
<? } ?>        
<?php $tabControl->Buttons() ?>
<input type="submit" name="Apply" value="Применить" title="Применить" /><?=bitrix_sessid_post();?>
<?php $tabControl->End() ?>
</form>
<?=BeginNote()?>
<p>Получить установленное значение можно так: <? 
echo str_replace('&lt;?', '', highlight_string('<? $val = COption::GetOptionString(\'conf\', \'имя настройки\');', true));?></p><?
EndNote();