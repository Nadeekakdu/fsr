<?php
require_once 'config/dbcon.php';
require_once 'config/iv_key.php';
require_once 'config/mystore_func.php'; //LOCAL

$conn = mysqli_connect(DB_HOST,DB_USERNAME,DB_PWD,DB_TBL);	
if($conn){
 //echo "connected";
}

$nic_no = "";
$app_confirm_status = 0;

if( (isset($_POST['inputNic'])) && ($_POST['inputNic'] != NULL) && ($_POST['inputNic'] != "") && ($_POST['inputNic'] != " ") ){
    $nic_no = trim($_POST['inputNic']);
    $nic_no = strtoupper($nic_no);
    $nic_no = mysqli_real_escape_string($conn,$nic_no);

    // perform a check to see applicant has confirm the application
    $sql_chk = "SELECT applicant_id FROM mst_personal_details WHERE nic_no = '$nic_no' AND application_confirm_status = 'Y' ";
    $res_chk = mysqli_query($conn,$sql_chk);

    $applicant_cnt = mysqli_num_rows($res_chk);
    if($applicant_cnt > 0){
        // already confirmed
        $app_confirm_status = 1;
    }else{
        $app_confirm_status = 0;
    } //end if
    // end check

    $enc_nic = encryptStoreStr($nic_no,ENCRYPT_METHOD,WSECRET_KEY,WSECRET_IV); //local
    //$enc_nic = $nic_no; //local

    if($app_confirm_status == 1){
        // redirect to status result page
        header('Location:applicationstatus.php?idn='.$enc_nic);
    }else{
        header('Location:applicationform.php?idn='.$enc_nic);
    }

}else{
    header('Location:index.php');
}
?>