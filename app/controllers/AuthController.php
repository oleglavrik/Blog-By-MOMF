<?php

namespace app\controllers;

use vendor\core\Controller;
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
                // get data
                $data['username'] = $_POST['username'];
                $data['password'] = md5($_POST['password']);
                $data['joined'] = date('Y-m-d H:i:s');

                // register user
                $auth = new Auth();
                $auth->registerUser($data);

                // redirect to home
                $this->redirectToRoute('/'); // todo change if it need
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
}