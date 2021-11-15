<?php
require_once 'config/dbcon.php';
require_once 'config/iv_key.php';
require_once 'config/mystore_func.php';
require_once 'config/global.php';

$conn = mysqli_connect(DB_HOST,DB_USERNAME,DB_PWD,DB_TBL);

$display_msg = "";
$app_cnfm_status = "N";
$enc_last_id = $_GET['lsidn'];
$dec_last_id = "";
$enc_nic_no = $_GET['idn'];
$dec_nic_no = "";
$dec_nic_no = decryptStr($enc_nic_no,ENCRYPT_METHOD,WSECRET_KEY,WSECRET_IV); 
$dec_last_id = decryptStr($enc_last_id,ENCRYPT_METHOD,WSECRET_KEY,WSECRET_IV); 
//$dec_nic_no = $enc_nic_no; 
//$dec_last_id = $enc_last_id; 
$academic_year = $row_academicYear['year'];

// check application status
//$sql_chk = "SELECT application_confirm_status FROM mst_personal_details WHERE nic_no = '$dec_nic_no' AND applicant_id = $dec_last_id ";
$sql_chk = "SELECT application_confirm_status FROM mst_personal_details WHERE nic_no = '$dec_nic_no' ";
$res_chk = mysqli_query($conn,$sql_chk);


$row_cnt = mysqli_num_rows($res_chk);
if($row_cnt > 0){
    while($row_chk = mysqli_fetch_array($res_chk)){
        $app_cnfm_status = $row_chk['application_confirm_status'];
    }
} // end if($row_cnt > 0)
//echo 'Confirm Status:'.$sql_chk.','.$app_cnfm_status;
if($app_cnfm_status == "Y"){
    $display_msg = "We have received your application form. Thank you for using our online application submission system.";
}else{
    $display_msg = "We encountered an error while saving your application form. Please try again";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>KDU-FGS</title>
        <link href="dist/css/styles.css" rel="stylesheet" />
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>-->
        <script src="dist/js/all-min.js"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h4 class="text-center font-weight-light my-4">APPLICATION FOR ADMISSION OF STUDENTS WITH FOREIGN QUALIFICATIONS FOR THE ACADEMIC YEAR  <?php echo $academic_year ?></h4></div>
                                    <div class="card-body">
                                        <form action="steponeaction.php" method="post">
                                            <div class="form-row">                                                
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="card  border-1 rounded-lg mt-2">
                                                        <div class="card-header"><h6>Application Status</h6></div>
                                                        <div class="card-body">
                                                        <?php
                                                        if($app_cnfm_status == 'Y'){
                                                        ?>
                                                            <div class="alert alert-success" role="alert">
                                                            <h4 class="alert-heading">Application saved successfully !</h4>
                                                            <p><?php echo $display_msg; ?></p>                                                                                                                                                                                   
                                                            </div>
                                                            <!--<form action="application_formpdf.php" method="post">
                                                                
                                                                
                                                            </form>-->
                                                            <a href="application_formpdf.php?lsidn=<?php echo $enc_last_id; ?>&idn=<?php echo $enc_nic_no; ?>"><input type="button" class="btn btn-primary btn-block" value="Download PDF version of my submitted application" /></a>
                                                        <?php
                                                        }else{
                                                        ?>
                                                            <div class="alert alert-danger" role="alert">
                                                            <h4 class="alert-heading">Error occured !</h4>
                                                            <p><?php echo $display_msg; ?></p>                                                                                                                         
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>                                                           
                                                        </div>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!--<div class="form-row">                                                
                                                <div class="col-lg-1 col-md-1 col-sm-2">
                                                    <div class="form-group mt-4 mb-0"><input type="submit" class="btn btn-primary btn-block" value="Next" />
                                                </div>                                                
                                            </div>-->
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="#">Application status</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-2">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; <?php echo date('Y');?> General Sir John Kotelawala Defence University</div>
                            <div>
                                <a href="#">Developed by CITS&DS</a>
                                &middot;
                                
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>-->
        <script src="dist/js/jquery-3-5-min.js"></script>
        <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>-->
        <script src="dist/js/bootstrap-bundle.js" crossorigin="anonymous"></script>
        <script src="dist/js/scripts.js"></script>
    </body>
</html>
