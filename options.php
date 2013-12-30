<? 
/*  Поле description в таблице b_option к сожалению после 14той версии не поддерживается штатным api ... 
    есть опасение что в следующих версиях его вообще грохнут нахер
    поэтому храню описания своих полей немного через жопу :(            */

$module_name = 'conf'; 
$res = $DB->Query('SELECT * FROM `b_option` WHERE `MODULE_ID` = "' . $module_name . '_descriptions"');
while ($option = $res->Fetch())
    $options[] = array('DESCRIPTION' => $option['VALUE'], 'NAME' => $option['NAME']);

$tabControl = new CAdminTabControl("tabControl", array(
    array("DIV" => "edit",
          "TAB" => 'Редактирование настроек',
          "TITLE" => 'Редактирование настроек'),
    array("DIV" => "add",
          "TAB" => 'Добавление настроек',
          "TITLE" => 'Добавление настроек'),
    array("DIV" => "help",
          "TAB" => 'Справка',
          "TITLE" => 'Справка')) 
);

if ((strlen($_REQUEST['Apply']) > 0) && check_bitrix_sessid()) {
    foreach ($options as $k => $option){
         if (isset($_REQUEST['conf'][$option['NAME']]['delete'])) {
            COption::RemoveOption($module_name, $option['NAME']);
            COption::RemoveOption($module_name . '_descriptions', $option['NAME']);
            unset($options[$k]); 
        } else {
            COption::SetOptionString($module_name, $option['NAME'], $_REQUEST['conf'][$option['NAME']]['val']);
        }
    }
    $_REQUEST['new_conf_name'] = array_filter($_REQUEST['new_conf_name']);
    if (count($_REQUEST['new_conf_name'])) {
        for ($i = 0; $i < count($_REQUEST['new_conf_name']); $i++) {
            COption::SetOptionString($module_name, $_REQUEST['new_conf_name'][$i], '');
            COption::SetOptionString($module_name . '_descriptions', $_REQUEST['new_conf_name'][$i], $_REQUEST['new_option_description'][$i]); 
        }
    }  
    LocalRedirect($GLOBALS['APPLICATION']->GetCurPage() . "?lang=" . (LANGUAGE_ID) . "&back_url_settings=" . urlencode($_REQUEST["back_url_settings"]));
}
$tabControl->Begin(); 
?>
<form  method="post" action="<?=$APPLICATION->GetCurPage()?>?lang=<?=LANGUAGE_ID?>"> <?
$tabControl->BeginNextTab();
if($options){?>
    <tr>
       <td width="2%"></td>
       <td width="20%"><b>Описание</b></td>
       <td width="40%"><b>Значение</b></td> 
       <td width="10%"></td>
       <td width="5%"><b>Удалить</b></td> 
    </tr><?
    foreach ($options as $option) { ?>
    <tr>  
       <td width="2%"></td>
       <td width="20%"><?=$option["DESCRIPTION"]?></td>
       <td width="40%"><input type="text" size="60" value="<?= COption::GetOptionString($module_name, $option['NAME']) ?>" name="conf[<?= $option['NAME'] ?>][val]" /></td>
       <td width="10%"><a href='#' onclick='prompt("Скопируйте код и вставьте в нужное место шаблона", "echo COption::GetOptionString(\"<?=$module_name;?>\",\"<?=$option['NAME']?>\");"); return false;'><?=$option["NAME"]?></a></td>
       <td width="5%"><input type="checkbox" name="conf[<?= $option['NAME'] ?>][delete]"></td>
     </tr><? }
} else {
    ShowError("Отсутствуют записи. Для добавления переключитесь на вкладку 'Добавление настроек'");
}
$tabControl->BeginNextTab();
for ($j = 1; $j <= 6; $j++) { ?> 
    <tr>
      <td width="45%"><input type="text" size="25" placeholder="Код настройки" name="new_conf_name[]"></td>
      <td width="45%"><input type="text" size="43" placeholder="Описание настройки" name="new_option_description[]"></td>
      <td width="10%"></td>
    </tr>
<? }
$tabControl->BeginNextTab();
?>
<p>Получить установленное значение можно так: <?=str_replace('&lt;?', '', highlight_string('<? $val = COption::GetOptionString(\'' . $module_name . '\', \'имя настройки\');', true)); ?></p>      
<? $tabControl->Buttons(); ?>
<input type="submit" name="Apply" value="Применить" title="Применить" /><?= bitrix_sessid_post(); ?>
<? $tabControl->End(); ?>
</form>