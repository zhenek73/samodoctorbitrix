<?
CModule::IncludeModule("iblock");



function pr($item, $show_for = false)
{
    global $USER;
    if ($USER->IsAdmin() || $show_for == 'all') {
        if (!$item) echo ' <br />empty <br />'; elseif (is_array($item) && empty($item)) echo '<br />array is ampty  <br />';
        else echo ' <pre>' . print_r($item, true) . ' </pre>';
    }
}


function pa($item, $show_for = false)
{
    if (!$item)
        echo ' <br />empty <br />';
    elseif (is_array($item) && empty($item))
        echo '<br />array is ampty  <br />';
    else
        echo ' <pre>' . print_r($item, true) . ' </pre>';

}


class Treatments
{
    private static $tiblock = 44;//курс лечения
    private static $miblock = 45;//курс лечения


    public function getTreatmentByUId($uid)
    {/*
    json гет запрос на получения списка курсов по ИД пользователя
    {
        "id": 10, ид курса
        "uid": "2", ид пользователя
        "description": "Лечение диабета", описание
        "name": "Диабет" имя
    }
    */
        $arFilter = Array("IBLOCK_ID" => self::$tiblock, "ACTIVE" => "Y", "PROPERTY_PATIENT" => $uid);
        $qrating = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
        $course=array();
        while ($fld = $qrating->GetNextElement()) {
            $fields = $fld->GetFields();
            $props = $fld->GetProperties();
            $course[]=array(
                'id' => $fields['ID'],
                'uid' => $props['PATIENT']['VALUE'],
                'description' => $fields['PREVIEW_TEXT'],
                'name' =>  $fields['NAME'],
            );
        }
        return $course;
    }
    public function getTreatmentByTId($еid)/*
        json гет запрос на получения списка лечебных назначений по ИД назначения
    */
    {
        $arFilter = Array("IBLOCK_ID" => self::$tiblock, "ACTIVE" => "Y", "ID" => $tid);
        $qrating = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());

        $course=array();

        while ($fld = $qrating->GetNextElement()) {
            $fields = $fld->GetFields();
            $props = $fld->GetProperties();
            $course[]=array(
                'id' => $fields['ID'],
                'uid' => $props['PATIENT']['VALUE'],
                'description' => $fields['PREVIEW_TEXT'],
                'name' =>  $fields['NAME'],
            );
        }

        return $course;
    }

    public function getIlnessByIId($id)/*
         гет запрос на получения информаци о болезни по ее ид
    */
    {
        $arFilter = Array("IBLOCK_ID" => self::$tiblock, "ACTIVE" => "Y", "ID" => $id);
        $qrating = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());

         =array();

        while ($fld = $qrating->GetNextElement()) {
            $fields = $fld->GetFields();
            $props = $fld->GetProperties();
            $course[]=array(
                'id' => $fields['ID'],
                'uid' => $props['PATIENT']['VALUE'],
                'description' => $fields['PREVIEW_TEXT'],
                'name' =>  $fields['NAME'],
            );
        }

        return $course;
    }

    public function getTreatmentByUIdFullData($uid)
    {
        $arFilter = Array("IBLOCK_ID" => self::$tiblock, "ACTIVE" => "Y", "PROPERTY_PATIENT" => $uid);
        $qrating = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
        $course=array();
        while ($fld = $qrating->GetNextElement()) {
            $fields = $fld->GetFields();
            $props = $fld->GetProperties();
            $activity=self::getActivityByTId($fields['ID']);
            $course[]=array(
                'id' => $fields['ID'],
                'uid' => $props['PATIENT']['VALUE'],
                'description' => $fields['PREVIEW_TEXT'],
                'name' =>  $fields['NAME'],
                'activity' => $activity
            );
        }
        return $course;
    }

    public function insertNewTreatment($uid,$name,$description)//Добавить новое лечение пациенту
    {
        $treatment = new CIBlockElement;

        $arFields = array(
            'IBLOCK_ID' =>  self::$tiblock,
            'ACTIVE' => 'Y',
            'NAME' => $name,
            'PREVIEW_TEXT'=>$description,
            'PROPERTY_VALUES' => array(
                 'PATIENT' => $uid,
            ),
        );
        $res = $treatment->Add($arFields);
        return $res;
    }

    public function getActivityByTId($tid)//получаем все лечебные мероприятия по АйДи Назначенного лечения. В рамках лечения то есть
    {
        $arFilter = Array("IBLOCK_ID" => self::$miblock, "ACTIVE" => "Y", "PROPERTY_TREATMENTID" => $tid);
        $qrating = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
        $activity = array();
        while($ald = $qrating->GetNextElement()) {
            $fields = $ald->GetFields();
            $props = $ald->GetProperties();
            $activity[]=array(
                'id' => $fields['ID'],
                'name' =>  $fields['NAME'],
                'doza' =>  $props['DOZA']['VALUE'],
                'begin' =>  $props['BEGIN']['VALUE'],
                'end' =>  $props['END']['VALUE'],
                'period' =>  $props['PERIOD']['VALUE'],
                'illness' =>  $props['PROBLEM']['VALUE'],
                'treatment' => $props['TREATMENTID']['VALUE']
            );
        }
        return $activity;
    }

    public function insertActivityForTreatment(
        $tid, //$tid - treatment ID
        $name,// названия мероприятия в рамках назначенного лечения. Строка
        $doza,// Дозировка препарата - строковое значение. Строка
        $begin,// начало выполнения лечебного мероприятия например начало курса приема витаминов. Таймстемп
        $end, // окончание выполнения лечебного мероприятия. Таймстемп
        $period,// период, через котороый, мероприятие должно повторятся - будет уведомление. Строка
        $illness// привязка к болезни или проблеме, которую пользователь хочет решить. ID болезней - зашьем жестко
        // для прототипа
    ) //Добавить новое мероприятие в рамках // назначенного лечение пациенту
    {
        $activity = new CIBlockElement;

        $arFields = array(
            'IBLOCK_ID' =>  self::$miblock,
            'ACTIVE' => 'Y',
            'NAME' => $name,
            'PROPERTY_VALUES' => array(
                'TREATMENTID' => $tid, //$tid - treatment ID
                'DOZA' => $doza,// Дозировка препарата - строковое значение
                'BEGIN' => $begin,// начало выполнения лечебного мероприятия например начало курса приема витаминов
                'END' => $end, // окончание выполнения лечебного мероприятия
                'PERIOD' => $period,// период, через котороый, мероприятие должно повторятся - будет уведомление
                'PROBLEM' => $illness// привязка к болезни или проблеме, которую пользователь хочет решить.
            ),
        );

        if($res = $activity->Add($arFields)){
            return $res;
        }else{
           pa(array('error'=>$activity->LAST_ERROR));
        }

    }

    public function takeAllActivities()//Обходим все лечебные мероприятия и сортируем их по пользователям
    {
        $arFilter = Array("IBLOCK_ID" => self::$miblock, "ACTIVE" => "Y");
        $qrating = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
        $activities = array();
        while($ald = $qrating->GetNextElement()) {
            $fields = $ald->GetFields();
            $props = $ald->GetProperties();

            $treatment_id =  $props['TREATMENTID']['VALUE'];
            $treatment = self::getTreatmentByTId($treatment_id);
            $illness = self::getIllnessByIId($props['PROBLEM']['VALUE']);
            $activities[]=array(
                'id' => $fields['ID'],
                'name' =>  $fields['NAME'],
                'doza' =>  $props['DOZA']['VALUE'],
                'begin' =>  $props['BEGIN']['VALUE'],
                'end' =>  $props['END']['VALUE'],
                'period' =>  $props['PERIOD']['VALUE'],
                'illness' =>  $illness,
                'treatment' =>  $treatment
            );
        }
        return $activities;
    }

}
?>