<?php
// Here it is validated if there is session and its behavior

$app->add(function ($request, $response, $next) {

    $path = $request->getUri();

    if ($path->getPath() !== 'auth' && !$request->isPost()){

        $headers = getallheaders();

        if(isset($headers['id_user']) && isset($headers['auth_token'])){
            $session = new Session();
            $active_session = $session->getSession($headers['auth_token'],$headers['id_user']);
            $current_time = time();
            
            if(!$active_session){
                http_response_code(401);
                die();
            }
            else{
                $time_elapsed = $current_time - $active_session["last_activity"];

                if($active_session['remember'] == 1){
                    if ( round($time_elapsed / 86400) > 30){
                        http_response_code(401);
                        die();
                    }
                }
                else{
                    if( ( $time_elapsed ) / 60 > 15 ){
                        $session->deleteSession($headers['auth_token'],$headers['id_user']);
                        http_response_code(401);
                        die();
                    }
                }
                $session->updateSession($headers['auth_token'],$headers['id_user'],$current_time);
            }
        }
        else{
            http_response_code(401);
            die();
        }
    }

    $response = $next($request, $response);

	return $response;
});