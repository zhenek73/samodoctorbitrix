<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Самодоктор");
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:main.auth.form",
    "",
    Array(
        "AUTH_FORGOT_PASSWORD_URL" => "",
        "AUTH_REGISTER_URL" => "",
        "AUTH_SUCCESS_URL" => ""
    )
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>