<?php
class BaseController {

    protected $f3;
    protected $database;
    protected $notificationsModel;
    protected $mobileDetect;

    function __construct() {
        $f3 = Base::instance();
        $this->f3=$f3;

        $database = new DB\SQL(
            $f3->get('DB'),
            $f3->get('DBUSER'),
            $f3->get('DBPASSWORD'),
            array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
        );

        $this->database=$database;
    }
    /**     * This will execute prior to every route.     */
    function beforeroute() {
       // $this->checkForCSRFAttack();
       // $this->checkMenuItems();
    }

    /**
     * Adds the latest pages to F3's storage, to display
     * them to the website visitors.
     */
    function checkMenuItems() {
        $model = new Page($this->database);
        $items = $model->all();
        $this->f3->set('menuitems', $items);
    }

    function checkLogin() {
        if($this->f3->get('SESSION.user_login') === null) {
            $this->f3->reroute('/login');
            exit;
        }
    }

    /**
     * This checks whether the current http request is a post. If it is, it will be checked
     * whether the possible session token is the same as the post token. If not, the session
     * will be terminated.
     */
    function checkForCSRFAttack() {
        if($this->f3->VERB=='POST') {
            $tyt = $this->f3->get('POST.token');
            $tyt1 = $this->f3->get('SESSION.csrf_old');
            $tyt12 = $_SESSION['csrf_old'];

            if($this->f3->get('POST.token') != $this->f3->get('SESSION.csrf_old')) {
                $user = $this->f3->exists('SESSION.user_login') ? $this->f3->get('SESSION.user_login') : "unknown user";
                $logger = new \Log(Logs::AUTH);
                $logger->write("Завершить сеанс для ". $this->f3->get('SESSION.user_login') . " Из-за атаки csrf");
                $logger->write("Kill session for " . $user . " due to csrf attack");
                $this->killSessionAndForceLogoff();
            }
        }
    }

    private function killSessionAndForceLogoff() {
        // Clear the session
        $this->f3->clear('SESSION');
        // Store new csrf token in session
        $this->f3->copy('CSRF','SESSION.csrf');
        // Display a warning to the user
        \Flash::instance()->addMessage("Завершен сеанс из-за атаки CSRF");
        // Reroute to login page and display error message
        $this->f3->reroute('@login');
        // Kill current request
        die();
    }
}
