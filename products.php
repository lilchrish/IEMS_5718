<?php
// Sample array
header('Access-Control-Allow-Origin: *');

$data = '{ "0": {"name": "Olumel-NY","price": 199}, "1": {"name": "Linabell-NY","price": 199}, "2": {"name": "Shelliemay-NY","price": 199}, "3": {"name": "Olumel-DG","price": 299}, "4": {"name": "Linabell-DG","price": 299}, "5": {"name": "Shelliemay-DG","price": 299}, "": {} }'; 
$pids = $_REQUEST['pid'];
$data = (array)json_decode($data);
$output = array();
if (is_array($pids)){
    foreach ($pids as $pid){
        $output[$pid]=(array) $data[$pid];
    }
}
else{
    $output[$pids]=(array) $data[$pids]; 
}
header("Content-Type: application/json");
echo json_encode($output);

exit();
?>
