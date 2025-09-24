<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $link = mysqli_connect('localhost', 'user', 'password', 'Database');
    mysqli_set_charset($link, 'utf8');
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    
    if($requestMethod == 'GET'){
        if(isset($_GET['course_id']) && !empty($_GET['course_id'])){
            $course_id = $_GET['course_id'];
            $sql = "SELECT * FROM courses WHERE course_id = '$course_id'";
        }else{
            $sql = "SELECT * FROM courses";
        }
        $result = mysqli_query($link,$sql);
        $arr = array();
        while($row = mysqli_fetch_assoc($result)){
            $arr[] = $row;
        }
        echo json_encode($arr);
    }
    $data = file_get_contents("php://input"); 
    $result = json_decode($data,true);
    if($requestMethod == 'POST'){
        if(!empty($result)){
            $course_id = $result['course_id'];
            $course_name = $result['course_name'];
            $coure_credit = $result['coure_credit'];
            $sql = "INSERT INTO courses(course_id, course_name, coure_credit)VALUES('$course_id', '$course_name', '$coure_credit')";
            $results = mysqli_query($link, $sql);
            if($results){
                echo json_encode(['status' => 'ok','message' => 'Insert Data Complete']);
            }else{
                echo json_encode(['status' => 'error','message' => 'Error']);
            }
        }else{
            echo json_encode(['status' => '500','message' => 'Nodata']);
        }
    }
    if($requestMethod == 'PUT'){
        if(!empty($result)){
            $course_key = $_GET['course_key'];
            $course_id = $result['course_id'];
            $course_name = $result['course_name'];
            $course_credit = $result['course_credit'];
            $sql = "UPDATE courses SETcourse_id = '$course_id', course_name = '$course_name', course_credit = '$course_credit' WHERE course_id = '$course_key'";
            $results = mysqli_query($link, $sql);
            if($results){
                echo json_encode(['status' => 'ok','message' => 'Update Data Complete']);
            }else{
                echo json_encode(['status' => 'error','message' => 'Error']);
            }
        }
    }
    if($requestMethod == 'DELETE'){
        if(isset($_GET['course_id']) && !empty($_GET['course_id'])){
            $course_id = $_GET['course_id'];
            $sql = "DELETE FROM courses WHERE course_id = '$course_id'";
            $results = mysqli_query($link, $sql);
            if($results){
                echo json_encode(['status' => 'ok','message' => 'Delete Data Complete']);
            }else{
                echo json_encode(['status' => 'error','message' => 'Error']);
            }
        }
    }
?>
