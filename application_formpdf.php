<?php
require_once 'config/dbcon.php';
require_once 'config/iv_key.php';
require_once 'config/mystore_func.php'; //local
require_once ('fpdf/fpdf.php');

$conn = mysqli_connect(DB_HOST,DB_USERNAME,DB_PWD,DB_TBL);	

// get POST values
$enc_nic_no = "";
$dec_nic_no = "";
//$enc_last_id = $_GET['lsidn'];
//$dec_last_id = "";
$enc_nic_no = $_GET['idn'];
//local
$dec_nic_no = decryptStr($enc_nic_no,ENCRYPT_METHOD,WSECRET_KEY,WSECRET_IV); 
//$dec_last_id = decryptStr($enc_last_id,ENCRYPT_METHOD,WSECRET_KEY,WSECRET_IV);
//$dec_nic_no = $enc_nic_no;
//$dec_last_id = $enc_last_id;


// get personal details
//$sql_get_personal = "SELECT * FROM mst_personal_details WHERE nic_no ='$dec_nic_no' AND applicant_id = $dec_last_id   ";
$sql_get_personal = "SELECT * FROM mst_personal_details WHERE nic_no ='$dec_nic_no' ";
$res_get_personal = mysqli_query($conn,$sql_get_personal);
$row_get_personal = mysqli_fetch_array($res_get_personal);
// --------------------
//echo 'sql:'.$sql_get_personal.'lid'.$enc_last_id;
// get EDUCATIONAL qualifications results
//$sql_edu_qual = "SELECT * FROM mst_educational_qualifications WHERE stu_nic = '$dec_nic_no' AND stu_id = $dec_last_id";
$sql_edu_qual = "SELECT * FROM mst_educational_qualifications WHERE stu_nic = '$dec_nic_no' ";
$res_edu_qual = mysqli_query($conn,$sql_edu_qual);

$edu_row_cnt = mysqli_num_rows($res_edu_qual);
// ---------------

// get professional memberships
/*$sql_prof_res = "SELECT * FROM mst_professional_qualification WHERE stu_nic = '$dec_nic_no' AND stu_id = $dec_last_id ";
$res_prof_res = mysqli_query($conn,$sql_prof_res);

$prof_row_cnt = mysqli_num_rows($res_prof_res);*/

// -----------------

// get employment details
/*$sql_emp_det = "SELECT * FROM mst_employment_history WHERE stu_nic = '$dec_nic_no' AND stu_id = $dec_last_id ";
$res_emp_det = mysqli_query($conn,$sql_emp_det);

$emp_row_cnt = mysqli_num_rows($res_emp_det);*/
// ----------------------

// get personal details
$stu_fullname = strtoupper($row_get_personal['stu_fullname']);
$name_initials = strtoupper($row_get_personal['stu_title'].". ".$row_get_personal['stu_name_initials']);
/*$stu_given_names = strtoupper($row_get_personal['stu_given_names']);*/
$stu_dob = $row_get_personal['stu_dob'];
$stu_gender = $row_get_personal['stu_gender'];
$stu_civil_status = $row_get_personal['civil_status'];
$stu_permenant_address = strtoupper($row_get_personal['stu_permenant_address']);
//$contact_no = $row_get_personal['stu_mobile'];
$email_addr = $row_get_personal['stu_email'];
$stu_nicno = $dec_nic_no;
$applied_course = $row_get_personal['course_name'];
$app_submit_dt = $row_get_personal['application_submit_dt'];

$stu_fullname = htmlentities($stu_fullname);
$name_initials = htmlentities($name_initials);
//$stu_given_names = htmlentities($stu_given_names);
$stu_dob = htmlentities($stu_dob);
$stu_gender = htmlentities($stu_gender);
$stu_civil_status = htmlentities($stu_civil_status);
$stu_permenant_address = htmlentities($stu_permenant_address);
/*$contact_no = htmlentities($contact_no);*/
$email_addr = htmlentities($email_addr);
$stu_nicno = htmlentities($stu_nicno);
$applied_course = htmlentities($applied_course);
$app_submit_dt = htmlentities($app_submit_dt);


class PDF extends FPDF
{
// Page header
	function Header()
	{
	    // Logo
	    $this->Image('images/Kotelawala_Defence_University_crest.png',10,6,30);
	    // Arial bold 15
	    $this->SetFont('Arial','B',12);
	    // Move to the right
	    $this->Cell(50);
		//$this->Ln(5);
	    // Title
	   // $this->Cell(50,10,'  GENERAL SIR JOHN KOTELAWALA DEFENCE UNIVERSITY APPLICATION FOR UNDERGRADUATE CADETSHIPS - INTAKE 38',0,0,'C');
	    // Line break
	    $this->Ln(20);
		$this->Ln(5);
		//$pdf->Cell(0,5,' ',0,1);
	}

	// Page footer
	function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',9);
//for($i=1;$i<=40;$i++)
	$pdf->Cell(0,5,' '.'',0,1);
	$pdf->Cell(0,5,'GENERAL SIR JOHN KOTELAWALA DEFENCE UNIVERSITY APPLICATION FOR FOREIGN STUDENTS DEGREE PROGRAMS',0,1);
	$pdf->Cell(0,5,' '.'',0,1);
	//$pdf->Cell(0,5,'Faculty of Graduate studies',0,1);
	$pdf->Cell(0,5,'General Sir John Kotelawala Defence University',0,1);
	$pdf->Cell(0,5,'Kandawala Road,',0,1);
	$pdf->Cell(0,5,'Rathmalana,',0,1);
	$pdf->Cell(0,5,'SRI LANKA.',0,1);
	$pdf->Cell(0,5,'Phone : +94-11-2634555',0,1);
	$pdf->Cell(0,5,'Email : admission@kdu.ac.lk',0,1);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,5,' '.'',0,1);
	
	$pdf->SetFillColor(193,229,252); // Background color of header
	$pdf->Cell(0,10,' PERSONAL DETAILS',0,1,'L',true);
	$pdf->SetFont('Arial','',9);
	// First row of data 
	$pdf->Cell(40,12,'Applied course',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,12,$applied_course,0,0,'L',false); // Second column of row 1 
	$pdf->Cell(0,5,' '.'',0,1);

	$pdf->Cell(40,12,'Application submit date',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,12,$app_submit_dt,0,0,'L',false); // Second column of row 1 
	$pdf->Cell(0,5,' '.'',0,1);

	$pdf->Cell(40,12,'Full Name',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,12,$stu_fullname,0,0,'L',false); // Second column of row 1 
	$pdf->Cell(0,5,' '.'',0,1);

	/*$pdf->Cell(40,10,'Given names',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,10,$stu_given_names,0,0,'L',false);
	$pdf->Cell(0,5,' '.'',0,1);*/

	//$pdf->Cell(0,5,'Name in full: '.$full_name,0,1);
	$pdf->Cell(40,10,'Name with initials',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,10,$name_initials,0,0,'L',false);
	$pdf->Cell(0,5,' '.'',0,1);	

	//$pdf->Cell(0,5,'Name with initials: '.$name_initials,0,1);
	$pdf->Cell(40,10,'Date of birth',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,10,$stu_dob,0,0,'L',false);
	$pdf->Cell(0,5,' '.'',0,1);

	$pdf->Cell(40,10,'Gender',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,10,$stu_gender,0,0,'L',false);
	$pdf->Cell(0,5,' '.'',0,1);

	$pdf->Cell(40,10,'Civil status',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,10,$stu_civil_status,0,0,'L',false);
	$pdf->Cell(0,5,' '.'',0,1);

	//$pdf->Cell(0,5,'DOB: '.$stu_dob,0,1);
	$pdf->Cell(40,10,'Postal Address',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,10,$stu_permenant_address,0,0,'L',false);
	$pdf->Cell(0,5,' '.'',0,1);
	//$pdf->Cell(0,5,'Postal Address: '.$postal_address,0,1);
	/*$pdf->Cell(40,10,'Contact No',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,10,$contact_no,0,0,'L',false);
	$pdf->Cell(0,5,' '.'',0,1);*/
	//$pdf->Cell(0,5,'Contact No: '.$contact_no,0,1);
	$pdf->Cell(40,10,'Email Address',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,10,$email_addr,0,0,'L',false);
	$pdf->Cell(0,5,' '.'',0,1);
	//$pdf->Cell(0,5,'Email: '.$email_addr,0,1);
	$pdf->Cell(40,10,'NIC No',0,0,'L',false); // First column of row 1 
	$pdf->Cell(150,10,$stu_nicno,0,0,'L',false);
	$pdf->Cell(0,5,' '.'',0,1);	
	//$pdf->Cell(0,5,'NIC No: '.$stu_nicno,0,1);
	
	$pdf->Cell(0,5,' ',0,1);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,10,'  EDUCATIONAL QUALIFICATIONS',0,1,'L',true);
	$pdf->SetFont('Arial','B',9);
	
	if($edu_row_cnt > 0){
		$pdf->Cell(0,5,' '.'',0,1);
		$pdf->Cell(80,8,'Year of exam',0,0,'L',false); // First header column 
		$pdf->Cell(80,8,'Name of the Exam',0,0,'L',false); // Second header column
		$pdf->Cell(80,8,'Subject & Grade',0,0,'L',false); // third header column
		$pdf->Cell(80,8,'Award ',0,0,'L',false); // fourth header column
		$pdf->Cell(0,10,' '.'',0,1);
		
		$pdf->SetFont('Arial','',9);
		
		while($row_edu_qual = mysqli_fetch_array($res_edu_qual)){
			$pdf->Cell(0,5,' '.'',0,1);
			$pdf->Cell(80,8,$row_edu_qual['exam_year'],0,0,'L',false);
			$pdf->Cell(80,8,$row_edu_qual['exam_name'],0,0,'L',false);
			$pdf->Cell(80,8,$row_edu_qual['subject_grade'],0,0,'L',false);
			$pdf->Cell(80,8,$row_edu_qual['award'],0,0,'L',false);
			$pdf->Cell(0,10,' '.'',0,1);
			//$pdf->Cell(0,5,''.$row_ol_res['SUBJECT_ID'].' - '.$row_ol_res['GRADE'],0,1);
		}
	}else{
		$pdf->Cell(0,5,' ',0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(0,10,'-',0,1,'L',true);
		$pdf->SetFont('Arial','B',9);
	}
	
	/*$pdf->Cell(0,5,' ',0,1);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,10,'  MEMBERSHIP OF PROFESSIONAL INSTITUTION',0,1,'L',true);
	
	if($prof_row_cnt > 0){
			$pdf->SetFont('Arial','B',10);	
			$pdf->Cell(0,5,' '.'',0,1);
			$pdf->Cell(80,8,'Institution',0,0,'L',false); // First header column 
			$pdf->Cell(80,8,'Membership category',0,0,'L',false); // Second header column
			$pdf->Cell(80,8,'Year of admission',0,0,'L',false); // Second header column
			$pdf->Cell(0,10,' '.'',0,1);

			$pdf->SetFont('Arial','',9);

		while($row_prof_res = mysqli_fetch_array($res_prof_res)){
			$pdf->Cell(0,5,' '.'',0,1);
			$pdf->Cell(80,8,$row_prof_res['institute_name'],0,0,'L',false); // First header column 
			$pdf->Cell(80,8,$row_prof_res['membership_cat'],0,0,'L',false); // Second header column
			$pdf->Cell(80,8,$row_prof_res['admit_yr'],0,0,'L',false); // Second header column
			$pdf->Cell(0,10,' '.'',0,1);
		}

	}*/
	

	/*$pdf->Cell(0,5,' ',0,1);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,10,'  EMPLOYMENT DETAILS',0,1,'L',true);

	if($emp_row_cnt > 0){
		$pdf->SetFont('Arial','B',10);	
		$pdf->Cell(0,5,' '.'',0,1);
		$pdf->Cell(80,8,'Company',0,0,'L',false); // First header column 
		$pdf->Cell(80,8,'Designation',0,0,'L',false); // Second header column
		$pdf->Cell(80,8,'Duration',0,0,'L',false); // Third header column
		$pdf->Cell(0,10,' '.'',0,1);

		$pdf->SetFont('Arial','',9);

		while($row_emp_res = mysqli_fetch_array($res_emp_det)){	
			$emp_duration = "";
			if($row_emp_res['present_employment'] == "Y"){
				$emp_duration = "Present Employment";
			}else{
				$emp_duration = htmlentities($row_emp_res['employment_duration']);
			}	
			$pdf->Cell(0,5,' '.'',0,1);
			$pdf->Cell(80,8,$row_emp_res['company_name'],0,0,'L',false); // First header column 
			$pdf->Cell(80,8,$row_emp_res['stu_designation'],0,0,'L',false); // Second header column
			$pdf->Cell(80,8,$emp_duration,0,0,'L',false); // Third header column
			$pdf->Cell(0,10,' '.'',0,1);
		}

	}
	*/
	
	/*$pdf->Cell(0,5,'General Sir John Kotelawala Defence University',0,1);
	$pdf->Cell(0,5,'Kandawala Road,',0,1);
	$pdf->Cell(0,5,'Rathmalana,',0,1);
	$pdf->Cell(0,5,'SRI LANKA.',0,1);
	$pdf->Cell(0,5,'Phone : 011-2622995',0,1);
	$pdf->Cell(0,5,'Email : db@kdu.ac.lk',0,1);
$pdf->SetFont('Times','',11);
    $pdf->Cell(0,10,'Payee Name: payee name',0,1);
	$pdf->Cell(0,10,'Description: '.$pay_desc,0,1);
	$pdf->Cell(0,10,'Payment Amount: '.$pay_amount." LKR",0,1);
	$pdf->Cell(0,10,'Payment Reference No: '.$payment_ref_no,0,1);
	$pdf->Cell(0,10,'PDF Generated Date: '.$pay_dt,0,1);*/
$pdf->Output();
?>