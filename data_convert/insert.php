<?php
$fn = fopen("gpu.txt","r");

$conn = new mysqli("localhost", "root", "", "nvsg_maskinvare");

$stmt = $conn->prepare("INSERT INTO gpu(name, score) VALUES (?, ?)");

while(! feof($fn))  {
  $name = fgets($fn);
  $score = fgets($fn);
  
  $stmt->bind_param("si", $name, $score);
  // if(!$stmt->execute()) {
  //     echo "Error";
  // }
}

fclose($fn);
?>