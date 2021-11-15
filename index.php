<?php
require_once 'config/dbcon.php';
require_once 'config/global.php';

$err_code = 0;
$display_message = "";
$academic_year = $row_academicYear['year'];

if( (isset($_GET['errcd'])) && ($_GET['errcd'] != NULL) && ($_GET['errcd'] != "") && ($_GET['errcd'] != " ") ){
    $err_code = $_GET['errcd'];
}


if($err_code == 1){
    $display_message = "Enter proper Passport number. Passport number cannot be blank.";
}else{
    // do nothing
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
        <title>KDU-FSR</title>
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
                                    <div class="card-header"><h4 class="text-center font-weight-light my-4">Application for Admission of Students with Foreign Qualifications for the Academic Year  <?php echo $academic_year ?></h4></div>
                                    <div class="card-body">
                                        <form action="steponeaction.php" method="post">
                                             <!-- <form method="post"> -->
                                            <?php
                                            if($err_code == 1){
                                            ?>
                                                <div class="form-row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="alert alert-danger" role="alert">
                                                        <i class="fa fa-warning"></i>&nbsp;<?php echo $display_message; ?>
                                                        </div>
                                                    </div>                                                
                                                </div>
                                            <?php
                                            }
                                            ?>

                                            <div class="form-row">                                                
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="card  border-1 rounded-lg mt-2">
                                                        <div class="card-header"><h6>Instructions for applicants</h6></div>
                                                        <div class="card-body">
                                                            <ul>
                                                                <li>Ensure to fill up the application with accurate details.</li>
                                                                <li>It is recommended and better to use Desktop PC or laptop to fill this online application.</li>
                                                                <li>It is recommended to use latest versions of Mozilla Firefox, Google chrome, Internet Explorer, opera, Netscape navigator web browsers.</li>
                                                                <li>You will require scanned copies of <b>NECESSARY DOCUMENTS</b>.<a href="document_list.php" target="/_blank">Please click here to see the details of <b>NECESSARY DOCUMENTS</b>.<a></li>
                                                                <li>Upload scanned copies of <b>NECESSARY DOCUMENTS (in PDF format)</b> to any storage like google drive, dropbox under a folder named by your passport number. Then get the publicly downloadable link and paste it in the application. <a href="https://youtu.be/GICuiiuxfAU" target="_blank">Please click here to watch the video.<a></li>
                                                                <li>Please note that you will not be able to edit your application once you submit it.</li>
                                                                <li>Application fee will not be refunded in the event of a rejection of application.</li>
                                                                <li>University has the exclusive right to shortlist the received application.</li>
                                                                <li>University is not responsible for not notifying applicants who provide incorrect details.</li>
                                                                <li>Application will also subjected to rejection if: </li>
                                                                <ul type="a">
                                                                    <li>The application is incomplete, in correct or not clear.</li>
                                                                    <li> There is an attempt to canvassing.</li>
                                                                </ul>
                                                            </ul>
                                                        </div>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="alert alert-warning" role="alert">
                                                    <i class="fa fa-warning"></i>&nbsp;Fields denoted with * are mandatory
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="form-row">
                                                <div class="col-lg-6 col-md-7 col-sm-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputNic">Student Passport Number <sup>*</sup></label>
                                                        <input class="form-control py-4" id="inputNic" name="inputNic" type="text" maxlength="12" minlength="10" required placeholder="Enter Passport number" />
                                                    </div>
                                                </div>  

                                                <!-- <div class="form-group">
                                                    <label class="small mb-1" for="inputDob">Date of birth <span class="error" style="color: #FF0000;">*</span></label> 
                                                    <input class="form-control" id="inputDob" name="inputDob" type="date" onchange="changeDateFormat()"  placeholder="Enter your Date of birth" />
                                                    
                                                </div> -->

                                            </div> 
                                            
                                            <div class="form-row">                                                
                                                <div class="col-lg-1 col-md-1 col-sm-2">
                                                    <div class="form-group mt-4 mb-0"><input type="submit" class="btn btn-primary btn-block" value="Next" />
                                                </div>                                                
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="#">Application step (1/2)</a></div>
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
