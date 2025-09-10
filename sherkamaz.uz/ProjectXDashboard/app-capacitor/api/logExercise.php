<?php
session_name("APPSESSID");  // ✅ must come BEFORE session_start
session_start();

require __DIR__ . '/cors.php';
require __DIR__ . '/bridgeSession.php';  // Now session is initialized
require __DIR__ . '/../../backend/config/db.php';

header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

require __DIR__ .'/../../backend/config/db.php';
//Check Login Session 
if(!isset($_SESSION['user_id']))
{
	//http_response_code(401); it is not authenitcated response 
	http_response_code(401);
	echo json_encode(["error"=>"Not Authenticated"]);
	exit;
}
// we are getting json ,turning it to php associative array
$data = json_decode(file_get_contents("php://input"),true);
//Validating whether $data is array 

if(!is_array($data))
{ 
	http_response_code(400);
	echo json_encode(["error"=>"Invalid data format"]);
	exit;
}
$userID = $_SESSION['user_id'];
$today = date("Y-m-d");
$inserted = 0;

$stmt = $pdo->prepare(
"INSERT INTO exercise(user_id,name,sets,reps,weight,status,created_at,mode)
VALUES(?,?,?,?,?,?,?,?)");

foreach($data as $exercise)
{
	//for safety check 
	if(!isset($exercise["name"]) || !is_array($exercise["sets"]))
		continue;
	$exerciseName = $exercise["name"];

	foreach($exercise["sets"] as $set )
	{
		$setCount = isset($set["sets"])?+($set["sets"]):0;
		$setReps = isset($set["reps"])?+($set["reps"]):0;	
		$setWeight = isset($set["weight"])?+($set["weight"]):0;
		$setStatus = isset($set["status"])?$set["status"]:0;
		$mode = isset($set["mode"])?$set["mode"]:"home";	
	$stmt ->execute([
		$userID,
		$exerciseName,
        $setCount,
        $setReps,
        $setWeight,
        $setStatus,
        $today,
        $mode
	    ]);
	$inserted++;
	}

}
echo json_encode(
["success"=>true,
 "response"=>"$inserted sets logged successfully"
]	
)
?>