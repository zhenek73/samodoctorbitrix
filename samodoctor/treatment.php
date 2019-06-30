<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/*
 * {
"id": 10,
"type": "drug",
"title": "Принять лекарство",
"description": "Принять B6",
"datetime": "1900-01-01 00:00:00"
}
 * */
header('Access-Control-Allow-Origin: *');
$error='';

$uid = $_REQUEST['uid'];// get user id
$full = $_REQUEST['full'];// get user id

if($uid == ''){//check if user id is
    $error = 'Error! No userID!';
    echo json_encode(array('error' => $error));
    die();
}
if($full==''){
    $courses = Treatments::getTreatmentByUId($uid);//get  prescribe treatment by user id
}else{
    $courses = Treatments::getTreatmentByUIdFullData($uid);//get  prescribe treatment by user id
}
pr($courses);
if(empty($courses)){ //throw exeption if there is no prescribe treatment for UID
    $error = 'Error! This user have no prescribe treatment for User ID!'.$uid;
    echo json_encode(array('error' => $error));
    die();
}

if(strlen($error)==0){
    print(json_encode($courses));
}else{
    echo json_encode(array('error' => $error));
}
?>