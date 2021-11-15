<?php
require_once 'config/dbcon.php';
require_once 'config/iv_key.php';
require_once 'config/mystore_func.php'; //local

$conn = mysqli_connect(DB_HOST,DB_USERNAME,DB_PWD,DB_TBL);	
if($conn){
 //echo "connected";
}

$enc_nic_no = "";
$dec_nic_no = "";
$err_code = 0;
$msg = "";
$app_confirm_status = 0;
$last_id = 0;
$enc_last_id = "";
$media_source_name = "Other";
$sql_personal_data = "";
date_default_timezone_set('Asia/Colombo');

if( (isset($_POST['inputNic'])) && ($_POST['inputNic'] != NULL) && ($_POST['inputNic'] != "") && ($_POST['inputNic'] != " ") ){
    $enc_nic_no = trim($_POST['inputNic']);
    $dec_nic_no = decryptStr($enc_nic_no,ENCRYPT_METHOD,WSECRET_KEY,WSECRET_IV);    //local
    //$dec_nic_no = $enc_nic_no; //local
    $dec_nic_no = mysqli_real_escape_string($conn,$dec_nic_no);

    // perform a check to see applicant has confirm the application
    $sql_chk = "SELECT applicant_id FROM mst_personal_details WHERE nic_no = '$dec_nic_no' AND application_confirm_status = 'Y' ";
    $res_chk = mysqli_query($conn,$sql_chk);

    $applicant_cnt = mysqli_num_rows($res_chk);
    if($applicant_cnt > 0){
        // already confirmed
        $app_confirm_status = 1;
    }else{
        $app_confirm_status = 0;
    } //end if
    // end check

    
    if($app_confirm_status == 1){
        // redirect to status result page
        header('Location:applicationstatus.php?idn='.$enc_nic_no);
    }else{
        // insert data personal data
        $apply_course_code = "";
        $apply_course = "";
        $intake_yr = "-";
        $stu_title = "";
        $stu_surname = "";
        $stu_givenname = "";
        $stu_initialname = "";
        $stu_dob = "";
        $stu_gender = "";
        
        $stu_civilstats = "";
        $stu_service_typ = "";
        $stu_rank = "";
        $stu_office_addr = "";
        $stu_home_addr = "";
        $stu_home_tel = "";
        $stu_country_birth = "";
        $stu_email = "";
        $doc_upld_link = "";
        $period_study_abroad ="";
        $eligibility_uni_admision =$_POST['$elegibleState'];
        $citizenship_type = "";
        $stu_citizenship = "";
        $citizenship1 = "";
        $citizenship2 = "";
        $country_AL = "";


        if($_POST['inputCourse'] != NULL && $_POST['inputCourse'] != "" && $_POST['inputCourse'] != " " ){$apply_course_code = $_POST['inputCourse'];}else{$err_code = 1;$msg = "Error! Mandatory field is missing : Course";}
        /*if($_POST['inputIntakeYr'] != NULL && $_POST['inputIntakeYr'] != "" && $_POST['inputIntakeYr'] != " " ){$intake_yr = $_POST['inputIntakeYr'];}else{$intake_yr = "-";}*/
        /*if($_POST['inputTitle'] != NULL && $_POST['inputTitle'] != "" && $_POST['inputTitle'] != " " ){$stu_title = $_POST['inputTitle'];}else{$err_code = 1;$msg = "Error! Mandatory field is missing : Title";}*/
        if($_POST['inputFullname'] != NULL && $_POST['inputFullname'] != "" && $_POST['inputFullname'] != " " ){$stu_fullname = $_POST['inputFullname'];}else{$err_code = 1;$msg = "Error! Mandatory field is missing : Full Name";}
       
        if($_POST['inputNameInitials'] != NULL && $_POST['inputNameInitials'] != "" && $_POST['inputNameInitials'] != " " ){$stu_initialname = $_POST['inputNameInitials'];}else{$err_code = 1;$msg = "Error! Mandatory field is missing : Initials";}
        if($_POST['inputDob'] != NULL && $_POST['inputDob'] != "" && $_POST['inputDob'] != " " ){
            $stu_dob = $_POST['inputDob'];
            $date1=new DateTime("2022-01-01");
            $date2= new DateTime($stu_dob);
            $interval = $date1->diff($date2);

            $myage= $interval->y; 
            $myage= $interval->y; 
            $age_mnth = $interval->m;
            $age_dt = $interval->d;
            $age_y_m_d=$myage.','.$age_mnth.','.$age_dt; 

            if ($myage <= 25 && $myage >= 17){ 
                 
                if ($myage == 25){ 
                    if($age_mnth > 0 || $age_dt > 0){
                            $err_code = 1;
                            $msg = "Your Age is should be above 17 and below 25!";
                    //echo "Invalid age 1 is:::".$myage  ."--".$age_mnth  ."--" .$age_dt;
                    }/*else{
                    echo "valid age is:".$age_y_m_d;
                    }*/ 
                } 
                
            }else{ 
                //cho "Invalid age is:".$age_y_m_d;
                $err_code = 1;
                $msg = "Your Age is should be above 17 and below 25!";
            }

        }else{
            $err_code = 1;
        }
        if($_POST['inputGender'] != NULL && $_POST['inputGender'] != "" && $_POST['inputGender'] != " " ){$stu_gender = $_POST['inputGender'];}else{$err_code = 1;$msg = "Error! Mandatory field is missing : Gender";}
        
        if($_POST['citizenship_type'] != NULL && $_POST['citizenship_type'] != "" && $_POST['citizenship_type'] != " " ){$citizenship_type = $_POST['citizenship_type'];}else{$citizenship_type = "-";}
        
        if($_POST['inputCivilSts'] != NULL && $_POST['inputCivilSts'] != "" && $_POST['inputCivilSts'] != " " ){$stu_civilstats = $_POST['inputCivilSts'];}else{$err_code = 1;$msg = "Error! Mandatory field is missing : Civilstatus";}
       if($_POST['inputCountryBirth'] != NULL && $_POST['inputCountryBirth'] != "" && $_POST['inputCountryBirth'] != " " ){$stu_birth_country = $_POST['inputCountryBirth'];}else{$err_code = 1;}
        if($_POST['addressPermanent'] != NULL && $_POST['addressPermanent'] != "" && $_POST['addressPermanent'] != " " ){$stu_permenant_addr = $_POST['addressPermanent'];}else{$stu_permenant_addr = "-";}
       
        if($_POST['inputEmailAddress'] != NULL && $_POST['inputEmailAddress'] != "" && $_POST['inputEmailAddress'] != " " ){$stu_email = $_POST['inputEmailAddress'];}else{$err_code = 1;$msg = "Error! Mandatory field is missing : Email";}
        if($_POST['inputMediaSource'] != NULL && $_POST['inputMediaSource'] != "" && $_POST['inputMediaSource'] != " " ){$media_source_name = $_POST['inputMediaSource'];}else{$media_source_name = "Other";}
        if($_POST['docupldlink'] != NULL && $_POST['docupldlink'] != "" && $_POST['docupldlink'] != " " ){$doc_upld_link = $_POST['docupldlink'];}else{$doc_upld_link = "-";}
        if($_POST['periodStudy'] != NULL && $_POST['periodStudy'] != "" && $_POST['periodStudy'] != " " ){$period_study_abroad = $_POST['periodStudy'];}else{$err_code = 1; $msg = "Error! Mandatory field is missing : Period of study abroad";}
        if(trim($_POST['refree1_details']) == "" && trim($_POST['refree2_details']) == "" && trim($_POST['refree_sl_details']) == "" ){$err_code = 1; $msg = "Error! Atleast one refree should be added";}

        if($err_code == 1){
            // redirect back to application form
            header('Location:applicationform.php?errcode=1');
        }else{
            // sanitize inputs
            $apply_course_code = mysqli_real_escape_string($conn,$apply_course_code);
            //$AcademicYear = trim($_POST['inputAcademicYear']);
            $intake_yr = trim($_POST['inputIntakeYr']);
            $intake_yr = mysqli_real_escape_string($conn,$intake_yr);
            $stu_title = mysqli_real_escape_string($conn,$stu_title);
            $stu_fullname = mysqli_real_escape_string($conn,$stu_fullname);
            $stu_birth_country = mysqli_real_escape_string($conn,$stu_birth_country);
            $stu_initialname = mysqli_real_escape_string($conn,$stu_initialname);
            $stu_dob = mysqli_real_escape_string($conn,$stu_dob);
            $stu_gender = mysqli_real_escape_string($conn,$stu_gender);
            $citizenship_type = mysqli_real_escape_string($conn,$citizenship_type);
            $stu_civilstats = mysqli_real_escape_string($conn,$stu_civilstats);
            $stu_permenant_addr = mysqli_real_escape_string($conn,$stu_permenant_addr);
            $stu_email = mysqli_real_escape_string($conn,$stu_email);
            $media_source_name = mysqli_real_escape_string($conn,$media_source_name);
            $doc_upld_link = mysqli_real_escape_string($conn,$doc_upld_link);
            $period_study_abroad = mysqli_real_escape_string($conn,$period_study_abroad);
            $eligibility_uni_admision = mysqli_real_escape_string($conn,$eligibility_uni_admision);
            $other_qualification = trim($_POST['otherQualifications']);
            $other_qualification = mysqli_real_escape_string($conn,$other_qualification);
            $fund = trim($_POST['fund']);
            $fund = mysqli_real_escape_string($conn,$fund);
            $stu_citizenship = trim($_POST['inputCitizenship']);
            $stu_citizenship = mysqli_real_escape_string($conn,$stu_citizenship);
            $citizenship1 = trim($_POST['inputCitizenship1']);
            $citizenship1 = mysqli_real_escape_string($conn,$citizenship1);
            $citizenship2 = trim($_POST['inputCitizenship2']);
            $citizenship2 = mysqli_real_escape_string($conn,$citizenship2);
            $country_AL = trim($_POST['inputCountryAL']);
            $country_AL = mysqli_real_escape_string($conn,$country_AL);

            // get apply course name
            $sql_cousr_name = "SELECT degree_name FROM mst_degree_courses WHERE degree_code = '$apply_course_code' ";
            $res_course_name = mysqli_query($conn,$sql_cousr_name);

            $course_name_cnt = mysqli_num_rows($res_course_name);
            if($course_name_cnt > 0){
                while($row_course_name = mysqli_fetch_array($res_course_name)){
                    $apply_course = $row_course_name['degree_name'];
                }
            }
            // ---------------------
            $cur_dt = date('Y-m-d H:i:s');
            $sql_personal_data = "INSERT INTO mst_personal_details (nic_no,course_name,course_code,intake,stu_title,stu_fullname,stu_name_initials,stu_dob,stu_gender,stu_citizenship,civil_status,stu_permenant_address,stu_email,application_submit_dt,media_source_name,doc_upload_link,birth_country,period_study_abroad,eligibility_uni_admision,other_qualification,fund,citizenship_type,citizenship_1,citizenship_2,AL_sitting_country)VALUES ('$dec_nic_no','$apply_course','$apply_course_code','$intake_yr','$stu_title','$stu_fullname','$stu_initialname','$stu_dob','$stu_gender','$stu_citizenship','$stu_civilstats','$stu_permenant_addr','$stu_email','$cur_dt','$media_source_name','$doc_upld_link','$stu_birth_country','$period_study_abroad','$eligibility_uni_admision','$other_qualification','$fund','$citizenship_type','$citizenship1','$citizenship2','$country_AL')";
            $res_personal_data = mysqli_query($conn,$sql_personal_data);

            $test_var = "";
            if($res_personal_data){
                $last_id = mysqli_insert_id($conn);
                $enc_last_id = encryptStoreStr($last_id,ENCRYPT_METHOD,WSECRET_KEY,WSECRET_IV);
                //$enc_last_id =$last_id;
                $edu_counter = $_POST['edurowcnt'];
                $edu_counter2 = $_POST['edurowcnt2'];
                
                
                $exam_name_al = trim($_POST['examNameAL']);
                $exam_name_al = mysqli_real_escape_string($conn,$exam_name_al);
                for($ei=1; $ei <= $edu_counter; $ei++){
                    
                    $subject_grade = trim($_POST['subject_AL_'.$ei]);
                    $subject_grade = mysqli_real_escape_string($conn,$subject_grade);
                    $award = trim($_POST['result_AL_'.$ei]);
                    $award = mysqli_real_escape_string($conn,$award);
                    $exam_year_al = trim($_POST['year_AL_'.$ei]);
                    $exam_year_al = mysqli_real_escape_string($conn,$exam_year_al);
                    
                    // insert educational qualifications
                    if($exam_year_al != "" && $exam_name_al != ""){
                        
                        $sql_educational = "INSERT INTO mst_educational_qualifications (stu_nic,exam_year,exam_name,exm_type,subject_grade,award,stu_id) VALUES ('$dec_nic_no','$exam_year_al','$exam_name_al','A/L','$subject_grade','$award',$last_id)";
                        $res_educational = mysqli_query($conn,$sql_educational);
                        echo $sql_educational;
                        if($res_educational){}else{$err_code = 2;}
                        
                    } // end if
                } // end for educational A/L

                
                $exam_name_ol = trim($_POST['examNameOL']);
                $exam_name_ol = mysqli_real_escape_string($conn,$exam_name_ol);
                for($ei=1; $ei <= $edu_counter2; $ei++){
                    
                    $subject_grade = trim($_POST['subject_OL_'.$ei]);
                    $subject_grade = mysqli_real_escape_string($conn,$subject_grade);
                    $award = trim($_POST['result_OL_'.$ei]);
                    $award = mysqli_real_escape_string($conn,$award);
                    $exam_year_ol = trim($_POST['year_OL_'.$ei]);
                    $exam_year_ol = mysqli_real_escape_string($conn,$exam_year_ol);
                    
                    // insert educational qualifications
                    if($exam_name_ol != ""){
                        
                        $sql_educational = "INSERT INTO mst_educational_qualifications (stu_nic,exam_year,exam_name,exm_type,subject_grade,award,stu_id) VALUES ('$dec_nic_no','$exam_year_ol','$exam_name_ol','O/L','$subject_grade','$award',$last_id)";
                        $res_educational = mysqli_query($conn,$sql_educational);
                        echo $sql_educational;
                        if($res_educational){}else{$err_code = 2;}
                        
                    } // end if
                } // end for educational O/L

                //english proficiency
                $tofelResult = trim($_POST['tofelResult']);
                $ieltsResult = trim($_POST['ieltsResult']);
                $satResult = trim($_POST['satResult']);
                $alResult = trim($_POST['alResult']);

                if(($tofelResult != "") || ($ieltsResult != "") || ($satResult != "") || ($alResult != "")){
                    //$tofelResult = trim($_POST['tofelResult']);
                    $tofelResult = mysqli_real_escape_string($conn,$tofelResult);
                    //$ieltsResult = trim($_POST['ieltsResult']);
                    $ieltsResult = mysqli_real_escape_string($conn,$ieltsResult);
                    //$satResult = trim($_POST['satResult']);
                    $satResult = mysqli_real_escape_string($conn,$satResult);
                    //$alResult = trim($_POST['alResult']);
                    $alResult = mysqli_real_escape_string($conn,$alResult);

                     
                    $sql_english = "INSERT INTO mst_english_proficiency (stu_passport_id,tofel_result,ielts_result,sat_result,al_result,stu_id) VALUES ('$dec_nic_no','$tofelResult','$ieltsResult','$satResult','$alResult',$last_id)";
                    $res_english = mysqli_query($conn,$sql_english);
                }
                //end of english proficiency

                //family_details
                // father details


                if(trim($_POST['fatherName'] != "")){
                    $fatherName = trim($_POST['fatherName']);
                    $fatherName = mysqli_real_escape_string($conn,$fatherName);
                    $fatherJob = trim($_POST['fatherJob']);
                    $fatherJob = mysqli_real_escape_string($conn,$fatherJob);
                    $father_employer = trim($_POST['father_employer']);
                    $father_employer = mysqli_real_escape_string($conn,$father_employer);
                    $fatherEmail = trim($_POST['fatherEmail']);
                    $fatherEmail = mysqli_real_escape_string($conn,$fatherEmail);
                    $fatherFixedPhone = trim($_POST['fatherFixedPhone']);
                    $fatherFixedPhone = mysqli_real_escape_string($conn,$fatherFixedPhone);
                    $fatherMobileNo = trim($_POST['fatherMobileNo']);
                    $fatherMobileNo = mysqli_real_escape_string($conn,$fatherMobileNo);

                    $sql_father = "INSERT INTO family_details (stu_passport_id,relationship,name,job,email,fixed_phone,mobile_no,employey_details,stu_id) VALUES ('$dec_nic_no','FATHER','$fatherName','$fatherJob','$fatherEmail','$fatherFixedPhone','$fatherMobileNo','$father_employer',$last_id)";
                    $res_father = mysqli_query($conn,$sql_father);
                }

                // mother details
                if(trim($_POST['motherName']  != "")){
                    $motherName = trim($_POST['motherName']);
                    $motherName = mysqli_real_escape_string($conn,$motherName);
                    $motherJob = trim($_POST['motherJob']);
                    $motherJob = mysqli_real_escape_string($conn,$motherJob);
                    $mother_employer = trim($_POST['mother_employer']);
                    $mother_employer = mysqli_real_escape_string($conn,$mother_employer);
                    $motherEmail = trim($_POST['motherEmail']);
                    $motherEmail = mysqli_real_escape_string($conn,$motherEmail);
                    $motherFixelPhone = trim($_POST['motherFixelPhone']);
                    $motherFixelPhone = mysqli_real_escape_string($conn,$motherFixelPhone);
                    $motherMobileNo = trim($_POST['motherMobileNo']);
                    $motherMobileNo = mysqli_real_escape_string($conn,$motherMobileNo);

                    $sql_mother = "INSERT INTO family_details (stu_passport_id,relationship,name,job,email,fixed_phone,mobile_no,employey_details,stu_id) VALUES ('$dec_nic_no','MOTHER','$motherName','$motherJob','$motherEmail','$motherFixelPhone','$motherMobileNo','$mother_employer',$last_id)";
                    $res_mother = mysqli_query($conn,$sql_mother);
                }

                // guardian details
                if(trim($_POST['guardianName']  != "")){
                    $guardianName = trim($_POST['guardianName']);
                    $guardianName = mysqli_real_escape_string($conn,$guardianName);
                    $guardianJob = trim($_POST['guardianJob']);
                    $guardianJob = mysqli_real_escape_string($conn,$guardianJob);
                    $guardian_employer = trim($_POST['guardian_employer']);
                    $guardian_employer = mysqli_real_escape_string($conn,$guardian_employer);
                    $guardianEmail = trim($_POST['guardianEmail']);
                    $guardianEmail = mysqli_real_escape_string($conn,$guardianEmail);
                    $guardianFixelPhone = trim($_POST['guardianFixelPhone']);
                    $guardianFixelPhone = mysqli_real_escape_string($conn,$guardianFixelPhone);
                    $guardianMobileNo = trim($_POST['guardianMobileNo']);
                    $guardianMobileNo = mysqli_real_escape_string($conn,$guardianMobileNo);

                    $sql_guardian = "INSERT INTO family_details (stu_passport_id,relationship,name,job,email,fixed_phone,mobile_no,employey_details,stu_id) VALUES ('$dec_nic_no','GUARDIAN','$guardianName','$guardianJob','$guardianEmail','$guardianFixelPhone','$guardianMobileNo','$guardian_employer',$last_id)";
                    $res_guardian = mysqli_query($conn,$sql_guardian);
                }
                // end of family details

                // refrees  
                if(trim($_POST['refree1_details'] != "")){
                    $refree1_details = trim($_POST['refree1_details']);
                    $refree1_details = mysqli_real_escape_string($conn,$refree1_details);
                    $refree1_phone = trim($_POST['refree1_phone']);
                    $refree1_phone = mysqli_real_escape_string($conn,$refree1_phone);
                    
                    $sql_refree1 = "INSERT INTO refree (stu_passport_id,refree_details,contact_no,type,stu_id) VALUES ('$dec_nic_no','$refree1_details','$refree1_phone','FOREIGN',$last_id)";
                    $res_refree1 = mysqli_query($conn,$sql_refree1);
                }

                if(trim($_POST['refree2_details'] != "")){
                    $refree2_details = trim($_POST['refree2_details']);
                    $refree2_details = mysqli_real_escape_string($conn,$refree2_details);
                    $refree2_phone = trim($_POST['refree2_phone']);
                    $refree2_phone = mysqli_real_escape_string($conn,$refree2_phone);
                    

                    $sql_refree2 = "INSERT INTO refree (stu_passport_id,refree_details,contact_no,type,stu_id) VALUES ('$dec_nic_no','$refree2_details','$refree2_phone','FOREIGN',$last_id)";
                    $res_refree2 = mysqli_query($conn,$sql_refree2);
                }

                if(trim($_POST['refree_sl_details'] != "")){
                    $refree_sl_details = trim($_POST['refree_sl_details']);
                    $refree_sl_details = mysqli_real_escape_string($conn,$refree_sl_details);
                    $refree_sl_phone = trim($_POST['refree_sl_phone']);
                    $refree_sl_phone = mysqli_real_escape_string($conn,$refree_sl_phone);
                    
                    $test_var = $refree_sl_details; 
                    $sql_refree_sl = "INSERT INTO refree (stu_passport_id,refree_details,contact_no,type,stu_id) VALUES ('$dec_nic_no','$refree_sl_details','$refree_sl_phone','SRILANKA',$last_id)";
                    $res_refree_sl = mysqli_query($conn,$sql_refree_sl);
                }
                // end of refree

                

                if($err_code == 0){
                    $sql_updt = "UPDATE mst_personal_details SET application_confirm_status = 'Y' WHERE nic_no = '$dec_nic_no' ";
                    $res_updt = mysqli_query($conn, $sql_updt);
                    if($res_updt){}else{$err_code = 8; }
                }

                if($err_code == 0){header('Location:applicationcnfm.php?idn='.$enc_nic_no.'&lsidn='.$enc_last_id.'&errCode='.$err_code);}else{header('Location:applicationform.php?errcode='.$err_code);}

            }else{
                header('Location:applicationform.php?errcode=2'.$sql_personal_data);
            } // end if($res_personal_data)

        } // end if($err_code == 1)
    }

}else{
    header('Location:index.php?errcd=1&nic='.$dec_nic_no);
}
?>