<?php

namespace vendor\core;

class Navigation extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_Function('buildNavigation', [$this, 'buildNavigation']),
            new \Twig_Function('buildNavigationAdmin', [$this, 'buildNavigationAdmin']),
        );
    }

    public function buildNavigation() {
        $html = '<ul class="nav navbar-nav navbar-right">';

        foreach (Config::get('navigation') as $key => $value) {
            $html .= '<li><a href='. $value .'>' . $key . '</a>';

            if (is_array($value)) {
                $html .= '<ul>';
                    foreach ($value as $subkey => $subvalue) {
                        $html .= '<li><a href='. $subvalue .'>' . $subkey . '</a></li>\n\t\t\t\t\t\t\t\t';
                    }
                $html .= '</ul>';
            }

            $html .= "</li>\n\t\t\t\t\t\t\t";
        }

        // show drop down item
        if(isset($_SESSION['auth'])) {
            $html .= '
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello,' . (isset($_SESSION['auth']['user_name']) ? $_SESSION['auth']['user_name'] : null) . '<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#"><i class="icon-cog"></i> Preferences</a></li>
                    <li><a href="#"><i class="icon-envelope"></i> Contact Support</a></li>
                    <li class="divider"></li>
                    <li><a href="/logout"><i class="icon-off"></i> Logout</a></li>
                </ul>
            </li>';

        }else {
            $html .= '
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome, Guest <b class="caret"></b></a>
                <div class="dropdown-menu">
                <form id="enter-form" method="post" action="/login">
                    <div class="form-group">
                        <label for="exampleInputEmail1">User name</label>
                        <input type="text" name="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-default">Login</button>
                </form>
                </div>
            </li>';
        }

        $html .= '</ul>';

        echo $html;
    }

    public function buildNavigationAdmin(){
        $html = '<ul class="nav navbar-nav navbar-right">';

        foreach (Config::get('navigation-admin') as $key => $value) {
            $html .= '<li><a href='. $value .'>' . $key . '</a>';

            if (is_array($value)) {
                $html .= '<ul>';
                foreach ($value as $subkey => $subvalue) {
                    $html .= '<li><a href='. $subvalue .'>' . $subkey . '</a></li>\n\t\t\t\t\t\t\t\t';
                }
                $html .= '</ul>';
            }

            $html .= "</li>\n\t\t\t\t\t\t\t";
        }

        $html .= '</ul>';

        echo $html;
    }
}

