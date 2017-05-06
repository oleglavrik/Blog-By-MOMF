<?php

return [
    'auth' => 'auth/authorization',
    'registration' => 'auth/registration',
    'post/add' => 'index/add',
    'post/show/([0-9]+)' => 'index/show/$1', // show simple post
    '__ajax__' => 'index/ajax', // service route for ajax load content
    '/' => 'index/index', // must be down
];

