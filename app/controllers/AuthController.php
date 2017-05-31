<?php

namespace app\controllers;

use app\models\auth\Session;
use vendor\core\Controller;
use vendor\core\FlashMessages;
use vendor\valitron\src\Valitron;
use app\models\auth\User;

class AuthController extends Controller
{
    /**
     * @return bool
     */
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
                // create current user model
                $user = new User();

                // check unique username
                if($user->checkUserName($_POST['username'])) {
                    // get data
                    $data['username'] = $_POST['username'];
                    $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $data['joined'] = date('Y-m-d H:i:s');

                    // register user
                    if($user->registerUser($data)) {
                        // create message
                        $message = new FlashMessages();
                        $message->setMessage('User successfully added.', 'success');

                        // redirect to home
                        $this->redirectToRoute('/'); // todo change if it need
                    }
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

    /**
     * @return bool
     */
    public function loginAction() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator =  new Valitron\Validator($_POST);

            $rules = [
                'required' => [
                    ['username'],
                    ['password'],
                ],
            ];

            $validator->rules($rules);

            if($validator->validate()) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                // get user data
                $user = new User();
                $userData = $user->getUserByUserName($username);

                if(password_verify($password, $userData['password'])) {
                    // create guard hash
                    $guardHash = md5($userData['password'] . $_SERVER['HTTP_USER_AGENT']);
                    // set cookie auth
                    setcookie('hash', $guardHash);

                    // set php session
                    $_SESSION['auth'] = [
                        'hash' => $guardHash,
                        'user_id' => $userData['id'],
                        'user_name' => $userData['username']
                    ];

                    // create session data
                    $sessionData['user_id'] = $userData['id'];
                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        $sessionData['ip'] = $_SERVER['HTTP_CLIENT_IP'];
                    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                        $sessionData['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    } else {
                        $sessionData['ip'] = $_SERVER['REMOTE_ADDR'];
                    }

                    $sessionData['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    $sessionData['time'] = date('Y-m-d H:i:s');
                    $sessionData['hash'] = $guardHash;

                    // set DB session
                    $session = new Session();
                    $session->setSession($sessionData);

                    // redirect to
                    if(isset($_SESSION['http_referrer']))
                    {
                        $this->redirectToRoute($_SESSION['http_referrer'], true); // referrer
                    } else {
                        $this->redirectToRoute('/'); // home
                    }
                }else {
                    // set validation error wrong username or password
                    $errorMessage = "Wrong username or password. Try again.";
                    $validator->error('username', $errorMessage);

                    echo $this->twig->render(
                        'auth/authorization.twig',
                        [
                            'errors' => $validator->errors()
                        ]
                    );

                    return true; // must be to stopping find the route
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

    public function logoutAction() {
        // unset db session
        if(!empty($_SESSION['auth']['user_id'])) {
            $session = new Session();
            $session->deleteUserSessionByUserID($_SESSION['auth']['user_id']);
        }

        // unset php session
        if(!empty($_SESSION['auth'])) {
            unset($_SESSION['auth']);
        }

        // unset COOKIE hash
        if(!empty($_COOKIE['hash'])) {
            setcookie("hash", null, time() - 3600, "/");
        }

        // redirect to login
        $this->redirectToRoute('/login');
    }
}
