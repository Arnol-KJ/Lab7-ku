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
        if(isset($_GET['student_id']) && !empty($_GET['student_id'])){
            $student_id = $_GET['student_id'];
            $sql = "SELECT * FROM students WHERE student_id LIKE '$student_id'";
        }else{
            $sql = "SELECT * FROM students";
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
            $student_id = $result['student_id'];
            $student_name = $result['student_name'];
            $gender = $result['gender'];
            $sql = "INSERT INTO students(student_id, student_name, gender)VALUES('$student_id', '$student_name', '$gender')";
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
            $student_code = $_GET['student_code'];
            $student_id = $result['student_id'];
            $student_name = $result['student_name'];
            $gender = $result['gender'];
            $sql = "UPDATE students SET student_id = '$student_id', student_name = '$student_name', gender = '$gender' WHERE student_id = '$student_code'";
            $results = mysqli_query($link, $sql);
            if($results){
                echo json_encode(['status' => 'ok','message' => 'Update Data Complete']);
            }else{
                echo json_encode(['status' => 'error','message' => 'Error']);
            }
        }
    }
    if($requestMethod == 'DELETE'){
        if(isset($_GET['student_id']) && !empty($_GET['student_id'])){
            $student_id = $_GET['student_id'];
            $sql = "DELETE FROM students WHERE student_id = '$student_id'";
            $results = mysqli_query($link, $sql);
            if($results){
                echo json_encode(['status' => 'ok','message' => 'Delete Data Complete']);
            }else{
                echo json_encode(['status' => 'error','message' => 'Error']);
            }
        }
    }

?>
