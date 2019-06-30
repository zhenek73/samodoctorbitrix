<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Access-Control-Allow-Origin: *');

$error='';
?>



<?
//http://iminister.site/samodoctor/insertactivity.php?tid=2320&name=Poloskanie%20rta&doza=1&cup&begin=2019-06-30T21:00:00&end=2019-07-06T21:00:00&period=24&illness=2305

$tid = $_REQUEST['tid'];//$tid - treatment ID
$name = $_REQUEST['name'];// названия мероприятия в рамках назначенного лечения. Строка
$doza = $_REQUEST['name'];// Дозировка препарата - строковое значение. Строка
$begin = $_REQUEST['begin'];// начало выполнения лечебного мероприятия например начало курса приема витаминов. Таймстемп
$end = $_REQUEST['end']; // окончание выполнения лечебного мероприятия. Таймстемп
$period = $_REQUEST['period'];// период, через котороый, мероприятие должно повторятся - будет уведомление. Строка
$illness = $_REQUEST['illness'];// привязка к бол


if($tid == ''){//check if user id is
    $error = 'Error! No Treatment ID!';
    echo json_encode(array('error' => $error));
    die();
}
if($name == ''){//check if user id is
    $error = 'Error! No Activity name!';
    echo json_encode(array('error' => $error));
    die();
}
if($doza == ''){//check if user id is
    $error = 'Error! Doza field is empty!';
    echo json_encode(array('error' => $error));
    die();
}
if($begin == ''){//check if user id is
    $error = 'Error! Begin field is empty!';
    echo json_encode(array('error' => $error));
    die();
}
if($period == ''){//check if user id is
    $error = 'Error! Period field is empty!';
    echo json_encode(array('error' => $error));
    die();
}
if($illness == ''){//check if user id is
    $error = 'Error! Illness field is empty!';
    echo json_encode(array('error' => $error));
    die();
}

$activitytid = Treatments::insertActivityForTreatment($tid,$name,$doza,$begin,$end,$period,$illness);//insert Activity treatment  for treatment id

if($activitytid>0){ //throw exeption if there is no prescribe treatment for UID
    $success = 'Actity for treatment is successfully added!';
    echo json_encode(array('success' => $success,'aid'=>$activitytid));
}else{
    $error='Error while adding Activity for treatment! '.$activitytid->LAST_ERROR;
    echo json_encode(array('error' => $error));
}
?>