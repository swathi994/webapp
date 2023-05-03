<?php
$username = filter_input(INPUT_POST, 'username');
$dob = filter_input(INPUT_POST, 'dob');
$displayname = filter_input(INPUT_POST, 'displayname');
if (!empty($username)){
if (!empty($dob)){
// Create connection
$conn = new mysqli ('db', 'root', 'example', 'assignment');
if (mysqli_connect_error()){
die('Connect Error ('. mysqli_connect_errno() .') '
. mysqli_connect_error());
}
else{
$sql = "INSERT INTO user (username, dob, displayname)
values ('$username','$dob', '$displayname')";
if ($conn->query($sql)){
echo "New record is inserted sucessfully";
}
else{
echo "Error: ". $sql ."
". $conn->error;
}
$conn->close();
}
}
else{
echo "dob should not be empty";
die();
}
}
else{
echo "username should not be empty";
die();
}
?>
