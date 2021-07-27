<?php
// secure single data show php

$data_query = "SELECT * FROM $tabel WHERE $whatby=?";
$stmt = mysqli_stmt_init($heart);
if(!mysqli_stmt_prepare($stmt, $data_query)){
echo "Error: " . $data_query  . "<br>" . $heart->error;
}
else {
mysqli_stmt_bind_param($stmt, "s", $by);
mysqli_stmt_execute($stmt);
$data_result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($data_result);
}

// secure all data with count show php
$data_query = "SELECT *, COUNT(*) AS totaldata FROM users";
$stmt = mysqli_stmt_init($heart);
if(!mysqli_stmt_prepare($stmt, $data_query)){
echo "Error: " . $data_query  . "<br>" . $heart->error;
}
else {
mysqli_stmt_execute($stmt);
$data_result = mysqli_stmt_get_result($stmt);
}
while($row = mysqli_fetch_assoc($data_result)){
echo $row['totaldata']."<br>";
echo $row['full_name']."<br>";
}


// secure update data base
// ===================== query ===============
$users = "UPDATE users SET full_name=?,email=?,mobile=?,address=?,about=? WHERE id=?";

$stmt = mysqli_stmt_init($heart);

if(!mysqli_stmt_prepare($stmt, $users )){
echo "Error: " . $users  . "<br>" . $heart->error;
}
else {

mysqli_stmt_bind_param($stmt, "ssssss", $full_name,$email,$mobile,$address,$user_about,$user_id);
mysqli_stmt_execute($stmt);
$lesupdate = mysqli_stmt_execute($stmt);
if($lesupdate){
    echo '<span class="alert alert-success block">Profile Update Success</span>';
}    
}// no sql error 



// secure pagination
// ===================== query ===============
$limit = 10;
if(isset($_GET['page'])){
$num_page = $_GET['page'];
} else {
$num_page= 1;
}
$pageoffset = ($num_page - 1) * $limit;
$donar_query = mysqli_query($heart,"SELECT * FROM donors ORDER BY id DESC LIMIT $pageoffset,$limit");
// ===================== query end ===============
// ===================== pagination excute ===============
$pagination = "SELECT * FROM donors";
$result1 = mysqli_query($heart, $pagination) or die("query Fail");
if (mysqli_num_rows($result1) > 0) {
$total_records = mysqli_num_rows($result1);
$total_pages = ceil($total_records / $limit);
echo '<ul class="pagination">';
	if($num_page > 1){
	echo '<li class="page-item">
		<a class="page-link" href="donar-list.php?page='.($num_page - 1).'">Previous</a>
	</li>';
	}
	for($i = 1; $i <= $total_pages; $i++){
	if($i == $num_page){
	$active = "active";
	} else {
	$active = "";
	}
	echo '<li class="page-item '.$active.'"><a class="page-link" href="donar-list.php?page='.$i.'">'.$i.'</a></li>';
	}
	if($total_pages > $num_page){
	echo '<li class="page-item">
		<a class="page-link" href="donar-list.php?page='.($num_page + 1).'">Next</a>
	</li>';
	}
echo '</ul>';
}
// ===================== pagination excute end===============
?>