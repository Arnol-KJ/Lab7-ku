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
            $sql = "SELECT E.*,S.student_name";
            $sql .= " FROM exam_result AS E";
            $sql .= " INNER JOIN students AS S ON E.student_id=S.student_id";
            $sql .= " WHERE E.course_id='$course_id'";
        }else{
            $sql = "SELECT E.*,S.student_name FROM exam_result AS E INNER JOIN students AS S ON E.student_id=S.student_id";
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
            $student_id = $result['student_id'];
            $point = $result['point'];
            $sql ="INSERT INTO exam_result(course_id, student_id, exam_point) VALUES('$course_id','$student_id', $point)";
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
            $student_code_edit = $_GET['student_code'];
            $student_id = $result['student_id'];
            $student_name = $result['student_name'];
            $point = $result['point'];

            $sql = "UPDATE exam_result AS E LEFT JOIN students AS S ON S.student_id = E.student_id SET E.student_id='$student_id', S.student_name='$student_name', E.exam_point=$point WHERE E.student_id='$student_code_edit'";

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
            $sql = "DELETE FROM exam_result WHERE student_id = '$student_id'";
            $results = mysqli_query($link, $sql);
            if($results){
                echo json_encode(['status' => 'ok','message' => 'Delete Data Complete']);
            }else{
                echo json_encode(['status' => 'error','message' => 'Error']);
            }
        }
    }
?>
