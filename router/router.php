<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


function logVisit($page_visited) {
    try{
        require "./dbo.php";
        $ip = $_SERVER['REMOTE_ADDR'];
        $conn = createDBInstance();

        $stmt = $conn->prepare("INSERT INTO website_visits(ip_address, page_visited) VALUES (?,?)");
        $stmt->bind_param("ss", $ip, $page_visited);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }catch(Exception $error){}
    
}

function use_router($app) {

    $app->get('/', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        logVisit('Main');
        return $view->render($response, 'home.page.twig');
    });

    $app->get('/about-me', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        // logVisit('About Me');
        return $view->render($response, 'about_me.page.twig');
    });

    $app->get('/services', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        // logVisit('About Me');
        return $view->render($response, 'services.page.twig');
    });

    $app->get('/niche-markets', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        // logVisit('Niche Markets');
        return $view->render($response, 'niche_markets.page.twig');
    });

    $app->post('/contact', function (Request $request, Response $response) {

        $validationErrors = array();

        try{

            $body = $request->getParsedBody();

            $fields = ['full_name', 'email', 'message', 'h-captcha-response'];

            foreach($fields as $field) {
                foreach($body as $key => $value){
                    if(!in_array($key, $fields) || !is_string($value)){
                        throw new Exception('FIELD_NOT_SET');
                    }
                }
            }

            if(verifyCaptchaToken($body['h-captcha-response'], $_SERVER['REMOTE_ADDR'])[0] === false){
                throw new Exception('FALSE_CAPTCHA');
            }

            function sanitize($value) {
                return trim(htmlspecialchars($value));
            }

            $fullName = $body["full_name"];
            $email = $body["email"];
            $message = $body["message"];


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

            sendContactInquiryAlertEmail($SAFE_email, $SAFE_fullName, $SAFE_message);

            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);


        }catch(Exception $e){
            $code;
            $payload;
            if($e->getMessage()){
                switch($e->getMessage()){
                    case "INVALID_REQUEST":
                        $code = 403;
                        $payload = ["error" => "INVALID_REQUEST"];
                        break;
                    case "FIELD_NOT_SET":
                        $code = 422;
                        $payload = ["error" => "MISSING_FIELD"];
                        break;
                    case "VALIDATION_ERRORS":
                        $code = 422;
                        $payload = ["error" => "VALIDATION_ERRORS", "details" => $validationErrors];
                        break;
                    case "FALSE_CAPTCHA":
                        $code = 422;
                        $payload = ["Error" => "FALSE_CAPTCHA", "details" => "Captcha Validation Failed."];
                        break;
                    default:
                        $code = 500;
                        $payload = ["error" => "SERVER_ERROR"];
                }
            }else{
                $code = 500;
                $payload = ["error" => "SERVER_ERROR"];
            }

            $response->getBody()->write(json_encode($payload));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($code);
        }


    });

}

?>