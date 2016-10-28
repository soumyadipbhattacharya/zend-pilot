<?php
include 'db.php';

$directory = 'uploads/V.7HolyCrossMarriages1823-1844';

$scanned_directory = array_diff(scandir($directory), array('..', '.'));
$success = 0;
$file_count =0;

/*
This portion dependent on folder naming convention. need to know this from delivery team. 
*/
$data = explode ('_',$directory);
$city = $data[4];
$county = $data[5];
$state = $data[6];
$country = $data[7];

if($city == ""){$city="Boston";}
if($county == ""){$county="Suffolk";}
if($state == ""){$state="Massachusetts";}
if($country == ""){$country="United-States";}


$detail = array(
				'city'=>$city,
				'county'=>$county,
				'state'=>$state,
				'country'=>$country,
				);
//				
$detail = serialize($detail);

foreach ($scanned_directory as $key => $value) {    

    //echo $value."</br>";
	$folder_name = "V.7HolyCrossMarriages1823-1844";
	$file_name = $value;
	$file_path = $directory."/".$file_name;
	//echo $file_path;
	$hash_value = hash_file('md5', $file_path);
	
	$check = "select count(id) as number from record where `file_name`='".$file_name."' and `file_hash`='".$hash_value."'";
	//echo $check;
	$check_result = mysqli_query($conn, $check) or die(mysqli_error($conn));
	$check_row = mysqli_fetch_array($check_result);
	
	//echo $check_row[number];
	
	if($check_row['number'] == 0){
		$file_count++;
		$statement = "insert into record (`folder_name`,`file_name`,`file_hash`,`upload_datetime`,`status`,`detail`,`qc_no`) values ('".$folder_name."','".$file_name."','".$hash_value."',NOW(),'0','".$detail."','0')";
		//echo $statement;
		mysqli_query($conn, $statement) or die(mysqli_error($conn));
		
	}
	
	$success++;
	
}

echo "File inserted in system: ".$file_count."<br><br>";
echo "Duplicate File found: ".($success-$file_count);
?>
