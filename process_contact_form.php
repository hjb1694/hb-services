<?php 

try{

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location:/');
        throw new Exception('INVALID_REQUEST');
    }

    $raw = file_get_contents("php://input");
    $content = trim($raw);
    $fields = ['full_name', 'email', 'message'];
    $data = json_decode($content, true);

    foreach($fields as $field) {
        foreach($data as $key => $value){
            if(!in_array($key, $fields) || !is_string($value)){
                throw new Exception('FIELD_NOT_SET');
            }
        }
    }

    function sanitize($value) {
        return trim(htmlspecialchars($value));
    }

    $fullName = $data["full_name"];
    $email = $data["email"];
    $message = $data["message"];

    $validationErrors = array();


    if(grapheme_strlen($fullName) < 2 || grapheme_strlen($fullName) > 50){
        array_push($validationErrors, 'Invalid Full Name');
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150){
        array_push($validationErrors, 'Invalid Email');
    }

    if(grapheme_strlen($message) < 10 || grapheme_strlen($message) > 1000){
        array_push($validationErrors, 'Invalid Message body');
    }

    if(count($validationErrors)){
        throw new Exception('VALIDATION_ERRORS');
    }

    $SAFE_fullName = sanitize($fullName);
    $SAFE_email = sanitize($email);
    $SAFE_message = sanitize($message);

    require "./dbo.php";

    $conn = createDbInstance();

    $stmt = $conn->prepare("INSERT INTO contact_inquiries(full_name, email, message_body) VALUES (?,?,?)");
    $stmt->bind_param("sss", $SAFE_fullName, $SAFE_email, $SAFE_message);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    http_response_code(201);
    echo json_encode(["message" => "submitted!"]);

}catch(Exception $e){
    if($e->getMessage()){
        switch($e->getMessage()){
            case "INVALID_REQUEST":
                http_response_code(403);
                echo json_encode(["error" => "INVALID_REQUEST"]);
                break;
            case "FIELD_NOT_SET":
                http_response_code(422);
                echo json_encode(["error" => "MISSING_FIELD", "raw" => $raw]);
                break;
            case "VALIDATION_ERRORS":
                http_response_code(422);
                echo json_encode(["error" => "VALIDATION_ERRORS", "details" => $validationErrors]);
                break;
            default:
                http_response_code(500);
                echo json_encode(["error" => "SERVER_ERROR"]);
        }
    }else{
        http_response_code(500);
        echo json_encode(["error" => "SERVER_ERROR"]);
    }
}




?>