<?php

return [
    'admin' => 'admin/index',
    'logout' => 'auth/logout',
    'login' => 'auth/login',
    'registration' => 'auth/registration',
    'post/add' => 'admin/add',
    'post/show/([0-9]+)' => 'index/show/$1', // show simple post
    '__ajax__' => 'index/ajax', // service route for ajax load content in main page
    '__admin_ajax__' => 'admin/ajax', // service route for ajax load post in Admin side
    '/' => 'index/index', // must be down
];

