<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Access-Control-Allow-Origin: *');

$error='';

$uid = $_REQUEST['uid'];// get user id
$name = $_REQUEST['name'];// get treatment name
$description = $_REQUEST['description'];// get treatment description

if($uid == ''){//check if user id is
    $error = 'Error! No userID!';
    echo json_encode(array('error' => $error));
    die();
}
if($name == ''){//check if user id is
    $error = 'Error! No Tratment name!';
    echo json_encode(array('error' => $error));
    die();
}
$treatmentid = Treatments::insertNewTreatment($uid,$name,$description       );//insert  prescribe treatment for user id

if($treatmentid>0){ //throw exeption if there is no prescribe treatment for UID
    $success = 'Prescribe treatment successfully added!';
    echo json_encode(array('success' => $success,'tid'=>$treatmentid));
}else{
    $error='Error while adding Prescribe treatment!';
    echo json_encode(array('error' => $error));
}
?>