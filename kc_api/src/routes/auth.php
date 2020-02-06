<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// This part can be improved, converting the content of the routes to classes

// Login
$app->post('/auth', function(Request $request, Response $response) {

    if ($request->getParam('username') && $request->getParam('password')) {
        $username = $request->getParam('username');
        $password = $request->getParam('password');

        $sql = "SELECT * FROM users WHERE username = '$username'";

        $db = new Connection();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        $db = null;

        if(isset($user)){
            if($user->password == sha1($password) ){

                $remember = 0;
                if($request->getParam('remember')){
                        $remember = intval($request->getParam('remember'));
                }
                $auth_token = md5(uniqid($user->id, true));
                $last_activity = time();
                $session = new Session();

                $session->deleteAllSessions(intval($user->id));

                $session->create(intval($user->id), $auth_token, $last_activity, $remember);

                echo(
                    json_encode(
                        [
                            "session" => [
                                "id_user" => intval($user->id),
                                "auth_token" => $auth_token,
                                "last_activity" => $last_activity,
                                "remember" => $remember
                            ]
                        ]
                    )
                );
            }
            else{
                http_response_code(401);
            }
        }
        else{
            http_response_code(401);
        }
    }
    else{
        http_response_code(400);
    }
});

// Logout
$app->delete('/auth', function(Request $request, Response $response) {
    $headers = getallheaders();

    if(isset($headers['id_user']) && isset($headers['auth_token'])){
        $session = new Session();
        echo json_encode($session->deleteSession($headers['auth_token'],$headers['id_user']));
    }
    else{
        http_response_code(400);
    }
});
