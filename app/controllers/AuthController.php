<?php

namespace app\controllers;

use app\models\auth\Sessions;
use vendor\core\Controller;
use vendor\core\FlashMessages;
use vendor\valitron\src\Valitron;
use app\models\auth\User;

class AuthController extends Controller
{
    private function checkUsername($userName) {
        // model check username in DB
        $login = new User();
        $login = $login->checkUserName($userName);

        if(empty($login))
            return true;
        else
            return false;
    }

    private function verifyUser($username) {
        // get user's data
        $user = new User();
        $user = $user->getUserByUserName($username);

        if(password_verify($_POST['password'], $user['password'])) {
            return true;
        }else {
            return false;
        }

    }

    /**
     * @param User $user
     * @return bool
     */
    private function setAuthSession(User $user) {
        $userData = $user;
        $userData = $userData->getUserByUserName($_POST['username']);

        // get user's id
        $sessionData['user_id'] = $userData['id'];

        // get user's ip
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $sessionData['ip'] = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $sessionData['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $sessionData['ip'] = $_SERVER['REMOTE_ADDR'];
        }
        // get user's agent
        $sessionData['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        // get user's time
        $sessionData['time'] = date('Y-m-d H:i:s');

        // get user's hash
        $sessionData['hash'] = md5($userData['password'] . $_SERVER['HTTP_USER_AGENT']);

        // set auth session
        $session = new Sessions();
        $result = $session->setSession($sessionData);

        return $result;
    }

    public function registrationAction() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // validation for registration form
            $validator =  new Valitron\Validator($_POST);

            $rules = [
                'required' => [
                    ['username'],
                    ['password'],
                    ['password_confirm'],
                ],
                'equals' => [
                    [
                        'password',
                        'password_confirm'
                    ]
                ],
                'lengthMin' => [
                    ['password', 6],
                    ['password_confirm', 6]
                ]
            ];

            $validator->rules($rules);

            if($validator->validate()) {
                if($this->checkUsername($_POST['username'])) {
                    // get data
                    $data['username'] = $_POST['username'];
                    $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $data['joined'] = date('Y-m-d H:i:s');

                    // register user
                    $usr = new User();
                    $usr->registerUser($data);

                    // create message
                    $message = new FlashMessages();
                    $message->setMessage('User successfully added.', 'success');

                    // redirect to home
                    $this->redirectToRoute('/'); // todo change if it need
                }else {
                    // set validation error username is already exist
                    $errorMessage = "This username " . "\"" . $_POST['username'] . "\"" . " is already exist, username must be unique";
                    $validator->error('username', $errorMessage);

                    echo $this->twig->render(
                        'auth/registration.twig',
                        [
                            'errors' => $validator->errors()
                        ]
                    );

                    return true; // must be to stopping find the route
                }


            }else {
                echo $this->twig->render(
                    'auth/registration.twig',
                    [
                        'errors' => $validator->errors()
                    ]
                );

                return true; // must be to stopping find the route
            }

        }

        echo $this->twig->render(
            'auth/registration.twig'
        );

        return true;

    }

    public function authorizationAction() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // validation for authorization
            $validator =  new Valitron\Validator($_POST);

            $rules = [
                'required' => [
                    ['username'],
                    ['password'],
                ],
            ];

            $validator->rules($rules);

            if($validator->validate()) {
                if($this->verifyUser($_POST['username'])) {
                    // set auth sessions
                    $user = $this->setAuthSession(new User());


                }else {
                    // set validation error wrong username or password
                    $errorMessage = "Wrong username or password, try to enter again.";
                    $validator->error('username', $errorMessage);

                    echo $this->twig->render(
                        'auth/authorization.twig',
                        [
                            'errors' => $validator->errors()
                        ]
                    );

                    return true;
                }

            }else {
                echo $this->twig->render(
                    'auth/authorization.twig',
                    [
                        'errors' => $validator->errors()
                    ]
                );

                return true; // must be to stopping find the route
            }
        }



        echo $this->twig->render(
            'auth/authorization.twig'
        );

        return true;
    }

}
