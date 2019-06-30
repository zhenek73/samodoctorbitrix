<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Access-Control-Allow-Origin: *');

$error='';

$uid = $_REQUEST['tid'];// get user id

if($tid == ''){//check if user id is
    $error = 'Error! No Treatment ID!';
    echo json_encode(array('error' => $error));
    die();
}
$courses = Treatments::getActivityByTId($tid);//get  prescribe acrivity  by treatment id

if(strlen($error)==0){
    print(json_encode($courses));
}else{
    echo json_encode(array('error' => $error));
}
?>