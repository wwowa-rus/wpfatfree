<?php  /* UserController php file */
namespace admin;
class UserController extends BaseAdminController {
    /*  Список всех пользователей */
    function all() {
        $status = ['0', 'admin', 'author', 'user'];
        $model = new \User($this->database);
        $users = $model->fieldId('id');
        $this->f3->set('status', $status);
        $this->f3->set('users', $users);
        $this->f3->set('view', 'admin/templates/admin_users.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');
    }
    /* Добавление пользователя из админки */
    function create() {
        $this->checkForCSRFAttack();
        if($this->f3->exists('POST.user_create')){
            $logger = new \Log(\Logs::CREATE);
            $login = $this->f3->get('POST.login');
            $email = $this->f3->get('POST.email');
            $status =  $this->f3->get('POST.status');
            $pass =  $this->f3->get('POST.pass');
            $registered = $this->f3->get('POST.registered');
            $v = new \Validator($this->f3->get('POST'));
            $v->rules([
                'lengthBetween' => [
                    ['login', 4, 15],
                    ['pass', 4, 15]
                ]
            ]);
            $v->rule('email', 'email');
            $v->rules([
                'regex' => [
                    ['pass', '/^([A-Za-z0-9-.;_!#@])+$/u'],
                    ['login', '/^([a-z0-9])+$/u']
                ]
            ]);
            if($v->validate()){
                $model = new \User($this->database);
                $find_rec = $model->find(array('login=? or email=?', $login, $email));
                if (!isset($find_rec[0])){
                    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
                        $arr_post = array(
                        'login'=> $login,
                        'email'=> $email,
                        'pass'=> $pass_hash,
                        'status'=> $status,
                        'registered' => $registered
                    );
                    $model->reset();
                    $model->addArray($arr_post);
                    $logger->write('User ' . $login . " Успешно добавлен из админки");
                    \Flash::instance()->addMessage('Вы успешно зарегистрировались', 'email_login');
                    $this->f3->reroute('@userall');
                }else{
                        $logger->write("Email или Login существуют");
                        \Flash::instance()->addMessage('Email или Login существуют', 'email_login');
                        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
                        $this->f3->reroute( '@usercreate');

                }
            }else{
                $errors = $v->errors();
                if(!empty($errors)){
                    foreach($errors as $error => $val){
                        \Flash::instance()->addMessage('поле ' . $error .':');
                        foreach($val as $err)
                        \Flash::instance()->addMessage('Ошибка: ' . $err);
                    }
                }
                $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
                $this->f3->reroute( '@usercreate');
            }
        }else{
            $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
            $this->f3->set('view', 'admin/templates/admin_user_create.htm');
            $template = new \Template();
            echo $template->render('admin/admin_layout.htm');
        }
    }
    /* Редактирование профиля пользователя из админки */
    function edit() {
        $this->checkForCSRFAttack();
        if($this->f3->exists('POST.edit_user')){
            $logger = new \Log(\Logs::CREATE);
            $id = $this->f3->get('PARAMS.item');
            $v = new \Validator($this->f3->get('POST'));
            $v->rules([
                'lengthBetween' => [
                    ['nicename', 4, 20],
                    ['name', 2, 20],
                    ['lastname',2, 20]
                ],
            'alpha'=> ['name','lastname'],
            'alphaNum'=> ['nicename'],
            'url'=> ['url']
            ]);
            if($v->validate()){
                $model = new \User($this->database);
                $model -> editPost($id);
                $logger->write("User " . $user_login . ' отредактировал профиль');
                $this->f3->reroute('@userall');
            }else{
                $errors = $v->errors();
                if(!empty($errors)){
                    foreach($errors as $error => $val){
                        \Flash::instance()->addMessage('поле ' . $error .':');
                        foreach($val as $err)
                        \Flash::instance()->addMessage('Ошибка: ' . $err);
                    }
                }
            $this->f3->reroute($this->f3->get('REALM'));
            }
        }else{
            $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
            $id = $this->f3->get('PARAMS.item');
            $model = new \User($this->database);
            $user = $model->getByID($id);
            $this->f3->set('user', $user);
            $this->f3->set('view', 'admin/templates/admin_user_edit.htm');
            $template = new \Template();
            echo $template->render('admin/admin_layout.htm');
        }
    }
     /* Удаление пользователя из админки */
    function delete(){
        $id = $this->f3->get('PARAMS.item');
        $user_login = $this->f3->get('SESSION.user_login');
        $user_login = $this->f3->get('SESSION.user_login');
        $find_post = $this->database->exec(
            'SELECT id FROM posts WHERE id>=? and user_id = '. $id .' LIMIT 1','1');
        $find_page = $this->database->exec(
            'SELECT id FROM pages WHERE id>=? and user_id = '. $id .' LIMIT 1','1');
        if(!empty($find_post) || !empty($find_page)){
            \Flash::instance()->addMessage('Удалите все посты и страницы этого автора');
        }else{
            $model = new \User($this->database);
            $model->delete($id);
            $logger = new \Log(\Logs::CREATE);
            $logger->write("User " . $user_login . ' Удалил пользователя');
            }
            $this->f3->reroute('@userall');
    }
}/* END Class */
