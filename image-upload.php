<?php

//secure image upload with check if image have

$images_file = $_FILES['job_images'];
$images_file_name = $_FILES['job_images']['name'];
$images_file_name = str_replace(" ", "-", $images_file_name);
$images_file_type = $_FILES['job_images']['type'];
$images_file_tmp_name = $_FILES['job_images']['tmp_name'];
$images_file_size = $_FILES['job_images']['size'];
$images_file_store = "job_sumbit_img/".$images_file_name;
$imageFileType = strtolower(pathinfo($images_file_store,PATHINFO_EXTENSION));

if (file_exists($_FILES["job_images"]["tmp_name"])) {
	
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {array_push($error, "ImagesFormatNotSupport");}
else {

if ($images_file_size > 3000000 ) {
array_push($error, "bigimage");
} 

else {

$u_check = "SELECT image FROM job_submit WHERE image=?";
$stmt = mysqli_stmt_init($heart);
if(!mysqli_stmt_prepare($stmt, $u_check)){
echo "Error: " . $u_check . "<br>" . $heart->error;
}
else {

mysqli_stmt_bind_param($stmt, "s", $images_file_name);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$job_submit_img = mysqli_stmt_num_rows($stmt);

if($job_submit_img > 0){

$actual_name = pathinfo($images_file_name,PATHINFO_FILENAME);
$original_name = $actual_name;
$extension = pathinfo($images_file_name, PATHINFO_EXTENSION);

$i = 1;
while(file_exists('job_sumbit_img/'.$actual_name.".".$extension))
{

$actual_name = (string)$original_name."-".$i;
$images_file_name = $actual_name.".".$extension;
$i++;

}

//folder name where image store
$images_file_store = "job_sumbit_img/".$images_file_name;

}


$upload = move_uploaded_file($images_file_tmp_name, $images_file_store);

if ($upload) {

$uploadwithimg = "INSERT INTO `job_submit`(`job_id`, `job_name`, `client_id`,`client_name`, `completed_by`, `job_proof_details`, `image`, `earn`)
VALUES
('$job_id','$job_name', '$client_id','$client_name', '$complete_by','$job_submit_deaitls','$images_file_name', '$earning_info')";

if (mysqli_query($heart, $uploadwithimg)) {
	array_push($error, "submitsuccess");
	unset($_SESSION['sub_details']);
}

else {
echo "Error: " . $uploadwithimg . "<br>" . mysqli_error($heart);
}//sql error

}
else{
array_push($error, "imagesuploaderror");
}// image upload error

}// image name no sql error


}// images size
}// image support
}// have image



?>