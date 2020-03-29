<?php
namespace security;
use BaseController;
use Template;
use User;
use Session;
use Logs;
use Flash;
class AuthController extends BaseController
{
    // Login Form Site
    public function render(){
        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
        // Виджеты шапки и сайдбарв вставки
        $widget_ins =  $this->database->exec('SELECT value FROM widget ');
        $this->f3->set('widget_ins', $widget_ins );
        $this->f3->set('view', 'theme/templates/login.htm');
        $template = new Template();
        echo $template->render('/theme/layout.htm');
    }
    //  authenticate route
    public function authlogin(){
        $this->checkForCSRFAttack();
        $login = $this->f3->get('POST.login');
        $pass = $this->f3->get('POST.pass');
        $logger = new \Log(Logs::AUTH);
        $logger->write("User " . $login . " пытается войти.");
        $v = new \Validator($this->f3->get('POST'));
        $v->rules([
            'lengthBetween' => [
                ['login', 4, 15],
                ['pass', 4, 15]
            ],
        'required' => ['login','pass'],
        'regex' => [
                ['pass', '/^([A-Za-z0-9-.;_!#@])+$/u'],
                ['login', '/^([a-z0-9])+$/u']
            ]
        ]);
        //$fields = array('id', 'login', 'pass');
        $model = new User($this->database);
        $model->load(array('login = ?',$login));
        if($v->validate() && !$model->dry() && password_verify($pass, $model->pass )){
            $this->f3->set('SESSION.user_login',$model->login);
            $logger->write("User " . $login . " вошел на сайт.");
            $this->f3->reroute('@admin');
            }
        else{
            // errors
            $logger->write("User " . $login . " -неудачная попытка войти.");
            Flash::instance()->addMessage('Неверный логин или пароль', 'bad_login');
            $errors = $v->errors();
            if(!empty($errors)){
                foreach($errors as $error => $val){
                    Flash::instance()->addMessage('поле ' . $error .':');
                   // Flash::instance()->addMessage(     $val, $error);
                    foreach($val as $err)
                    Flash::instance()->addMessage('Ошибка: ' . $err);
                }
            }
                $this->f3->reroute('@login');
            }
    }
    function registration(){
        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
        // Виджеты шапки и сайдбарв вставки
        $widget_ins =  $this->database->exec('SELECT value FROM widget ');
        $this->f3->set('widget_ins', $widget_ins );
        $this->f3->set('view', 'theme/templates/registration.htm');
        $template = new Template();
        echo $template->render('/theme/layout.htm');
    }

    function authreg(){
            $this->checkForCSRFAttack();
            // setup var log
            $login = $this->f3->get('POST.login');
            $email = $this->f3->get('POST.email');
            $pass = $this->f3->get('POST.pass');
            $logger = new \Log(Logs::AUTH);
            $logger->write("User " . $login . " пытается Зарегистрироваться.");
            $v = new \Validator($this->f3->get('POST'));
            $v->rules([
                'lengthBetween' => [
                    ['login', 4, 15],
                    ['pass', 4, 15]
                ],
                'required' => ['login','pass'],
                'email' => [
                    ['email']
                ],
                'regex' => [
                        ['pass', '/^([A-Za-z0-9-.;_!#@])+$/u'],
                        ['login', '/^([a-z0-9])+$/u']
                    ]
            ]);
            if($v->validate()){
                $model = new User($this->database);
                $find_rec = $model->find(array('login=? or email=?', $login, $email));
                    if (!isset($find_rec[0])){
                        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
                            $arr_post = array(
                            'login'=> $login,
                            'email'=> $email,
                            'pass'=> $pass_hash,
                            'registered' => date('Y-m-d')
                            );
                        $model->reset();
                        $model->copyFrom($arr_post);
                        $model->save();
                        $logger->write("Email and Login в порядке");
                        Flash::instance()->addMessage('Вы успешно зарегистрировались', 'email_login');
                    }else{
                        $logger->write("Email или Login существуют");
                        Flash::instance()->addMessage('Email или Login существуют', 'email_login');
                        $this->f3->reroute('@reg');
                    }
                $this->f3->reroute('@login');
            }else{
                // errors
                $logger->write("User " . $login . " -неудачная попытка регистрации");
                Flash::instance()->addMessage('Неверный формат логина или пароля', 'bad_login');
                $errors = $v->errors();
            if(!empty($errors)){
                foreach($errors as $error => $val){
                    Flash::instance()->addMessage('поле ' . $error .':');
                   // Flash::instance()->addMessage(     $val, $error);
                    foreach($val as $err)
                    Flash::instance()->addMessage('Ошибка: ' . $err);
                }
            }
            $this->f3->reroute('@reg');
            }
        }

    public function logOff()
    {
        $username = $this->f3->get('SESSION.user_login');
        if ($username !== null) {
            // Clear the session
            $this->f3->clear('SESSION');
            // Store new csrf token in session
            $this->f3->copy('CSRF', 'SESSION.csrf');
        }
        // Reroute to login page
        $this->f3->reroute('@login');
    }
}
