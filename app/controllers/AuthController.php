<?php

namespace app\controllers;

use vendor\core\Controller;
use vendor\core\FlashMessages;
use vendor\valitron\src\Valitron;
use app\models\auth\Auth;

class AuthController extends Controller
{
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
                    $authModel = new Auth();
                    $authModel->registerUser($data);

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

        return true; // must be to stopping find the route

    }

    protected function checkUsername($userName) {
        // model check username in DB
        $login = new Auth();
        $login = $login->checkUserName($userName);

        if(empty($login))
            return true;
        else
            return false;
    }

}
