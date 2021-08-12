<?php
//================================================================
// setup a variable and populate it with the data returned 
// from the call to the instance metadata instance id value
//================================================================
$this_instance_id = file_get_contents('http://169.254.169.254/latest/meta-data/instance-id');

//=================================================================
// as long as the instance id is not empty, display it otherwise, 
// return a Service Unavailable 503 code and a brief error message
//=================================================================
if(!empty($this_instance_id)) {
	echo (string)($this_instance_id);
} 
else {
    http_response_code(503);
	echo "Sorry, instance id unknown";
}