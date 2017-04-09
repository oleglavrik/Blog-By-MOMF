<?php

namespace vendor\core;

class FlashMessages extends \Twig_Extension
{
    public function getName()
    {
        return 'FlashMessages';
    }


    public function getFunctions()
    {
        return array(
            new \Twig_Function('showMessages', [$this, 'showMessages']),
        );
    }

    public function setMessage($message, $status)
    {
        $_SESSION['flash'] = [
            'status' => $status,
            'message' => $message
        ];
    }

    public function showMessages()
    {
        if (!empty($_SESSION['flash'])) {
            echo '<div class="alert alert-' . $_SESSION['flash']['status'] . '" >';
            echo $_SESSION['flash']['message'];
            echo '</div>';

            unset($_SESSION['flash']);
        }
    }
}