<?php

return [
    'admin'                => 'admin/index',
    'logout'               => 'auth/logout',
    'login'                => 'auth/login',
    'registration'         => 'auth/registration',
    'post/add'             => 'post/add',
    'post/delete/([0-9]+)' => 'post/delete/$1', // delete simple post
    'post/show/([0-9]+)'   => 'index/show/$1', // show simple post
    'post/edit/([0-9]+)'   => 'post/edit/$1', // edit simple post
    '__ajax__'             => 'index/ajax', // service route for ajax load content in main page
    '__admin_ajax__'       => 'admin/ajax', // service route for ajax load post in Admin side
    '/'                    => 'index/index', // must be down
];

