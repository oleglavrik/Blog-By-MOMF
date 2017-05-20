<?php

namespace vendor\core;

use app\models\auth\Sessions;
use app\models\auth\User;
use vendor\core\Interfaces\IController;
use vendor\core\FlashMessages;
use vendor\core\Request;
use app\models\auth\Session;

class Controller implements IController
{
    protected $twig;

    const VIEW_DIR = __DIR__ . '/../../app/views';

    public function __construct()
    {
        // init twig
        $loader = new \Twig_Loader_Filesystem(self::VIEW_DIR);
        $this->twig = new \Twig_Environment($loader);
        // add show messages extension
        $this->twig->addExtension(new FlashMessages());
    }

    public function error404() {
        echo $this->twig->render('exception/error404.twig');

        return true;
    }

    public function redirectToRoute($route, $referrerClear = false, $statusCode = 303){
        if($referrerClear){
            unset($_SESSION['http_referrer']);
        }

        header('Location: ' . $route, true, $statusCode);
        die();
    }

    public function securityAuth(Request $requestObj) {
        // get request object
        $request = $requestObj;

        // create user session
        $userSession = new Session();

        // check auth php session
        if(!empty($_SESSION['auth']['hash'])) {
            /* Session is available */

            // get user id from php session
            $userID = $_SESSION['auth']['user_id'];

            // get current user data
            $arr['user_id'] = $userID;
            $arr['ip'] = $_SERVER['REMOTE_ADDR'];
            $arr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $arr['hash'] = $_COOKIE['hash'];

            // find user session
            $activeUserSession = $userSession->findActiveSessionByUserData($arr);

            if($activeUserSession['count'] == 0) {
                /**
                 * illegal user access
                 * delete al user's DB sessions
                 * unset user's cookie and session
                 * redirect to login route
                 */

                if(!empty($arr['user_id'])) {
                    $userSession->deleteUserSessionByUserID($arr['user_id']);
                }

                setcookie("hash", null, time() - 3600, "/");
                unset($_SESSION['auth']);
                $this->redirectToRoute('/login');
            }

            return true;
        }else {
            /**
             * Session isn't available
             * check if cookie hash is available
             */
            if(!empty($_COOKIE['hash'])) {
                /**
                 * Cookie hash isn't empty
                 * compare cookie hash and DB session hash
                 */

                $userData = $userSession->getUserSessionByHash($_COOKIE['hash']);

                // compare cookie hash and db session hash!
                if($_COOKIE['hash'] === $userData['hash']) {
                    /**
                     * db session isset
                     * only set up php session
                     */
                    $_SESSION['auth']['hash'] = $userData['hash'];
                    $_SESSION['auth']['user_id'] = $userData['user_id'];
                } else {
                    /**
                     * Cookie hash and user session hash are not equal
                     * remove cookie hash
                     * set http referrer into php session
                     * redirect to login
                     */
                    setcookie("hash", null, time() - 3600, "/");
                    $request->setSessionReferrer();
                    $this->redirectToRoute('/login');
                }
            }else {
                /**
                 * Cookie hash isn't available
                 * set http referrer into php session
                 * redirect to login
                 */
                $request->setSessionReferrer();
                $this->redirectToRoute('/login');
            }

        }
    }

}