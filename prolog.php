<?
function getconf($param){
    return COption::GetOptionString('conf', $param, '');
}