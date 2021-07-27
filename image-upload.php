<?php
// ===================== Image Upload ===================
$images_file = $_FILES['upload_images'];
$images_file_name = $_FILES['upload_images']['name'];
$images_file_name = str_replace(" ", "-", $images_file_name);
$images_file_type = $_FILES['upload_images']['type'];
$images_file_tmp_name = $_FILES['upload_images']['tmp_name'];
$images_file_size = $_FILES['upload_images']['size'];
$images_file_store = "user_image/".$images_file_name;
$imageFileType = strtolower(pathinfo($images_file_store,PATHINFO_EXTENSION));

if (file_exists($_FILES["upload_images"]["tmp_name"])) {
    
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {array_push($error, "ImagesFormatNotSupport");}
else {
if ($images_file_size > 3000000 ) {
array_push($error, "bigimage");
} 
else {
$u_check = "SELECT image FROM users WHERE image=?";
$stmt = mysqli_stmt_init($heart);
if(!mysqli_stmt_prepare($stmt, $u_check)){
echo "Error: " . $u_check . "<br>" . $heart->error;
}
else {
mysqli_stmt_bind_param($stmt, "s", $images_file_name);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$users_img = mysqli_stmt_num_rows($stmt);
if($users_img > 0){
$actual_name = pathinfo($images_file_name,PATHINFO_FILENAME);
$original_name = $actual_name;
$extension = pathinfo($images_file_name, PATHINFO_EXTENSION);
$i = 1;
while(file_exists('user_image/'.$actual_name.".".$extension))
{
$actual_name = (string)$original_name."-".$i;
$images_file_name = $actual_name.".".$extension;
$i++;
}
//folder name where image store
$images_file_store = "user_image/".$images_file_name;
}
$upload = move_uploaded_file($images_file_tmp_name, $images_file_store);

if ($upload) {

$uploadwithimg = "UPDATE users SET image=? WHERE id=?";
$stmtimg = mysqli_stmt_init($heart);
if(!mysqli_stmt_prepare($stmtimg, $uploadwithimg )){
echo "Error: " . $uploadwithimg  . "<br>" . $heart->error;
}
else {
mysqli_stmt_bind_param($stmtimg, "ss", $images_file_name, $id);
mysqli_stmt_execute($stmtimg);
$lesupdate = mysqli_stmt_execute($stmtimg);
if($lesupdate){
    echo '<span class="alert alert-success block">Image Update Success</span>';
}
}// no sql error 

}
else{
echo "Images Upload Error";
}// image upload error
}// image name no sql error
}// images size
}// image support
}// have image
// ===================== Image Upload End===================
?>