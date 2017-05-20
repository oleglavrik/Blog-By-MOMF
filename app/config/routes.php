<?php

return [
    'admin' => 'admin/index',
    'logout' => 'auth/logout',
    'login' => 'auth/login',
    'registration' => 'auth/registration',
    'post/add' => 'index/add',
    'post/show/([0-9]+)' => 'index/show/$1', // show simple post
    '__ajax__' => 'index/ajax', // service route for ajax load content
    '/' => 'index/index', // must be down
];

