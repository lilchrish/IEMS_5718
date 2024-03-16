<?php
// Sample array
header('Access-Control-Allow-Origin: *');

$data = '{ "0": {"name": "Apple","price": 20}, "1": {"name": "Orange","price": 10}, "2": {"name": "Book","price": 2} }';

//$data = '[ {"name": "Apple","price": 20},{"name": "Orange","price": 10}, {"name": "Book","price": 2}]';

$pids = $_REQUEST['pid'];

$data = (array) json_decode($data);

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
