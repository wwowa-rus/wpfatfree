<?php
namespace admin;

class PageController extends BaseAdminController {

   /*  Список всех постов */
    function all() {
        $model = new \Page($this->database);
        $model->author= 'SELECT login FROM users '.
        'WHERE users.id = pages.user_id';
        $pages = $model->fieldId('id');
        $this->f3->set('pages', $pages);
        $this->f3->set('view', 'admin/templates/admin_pages.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');
    }
function create() {
    $this->checkForCSRFAttack();
    if($_POST){
        $slug = $this->f3->get('POST.slug');
        $user_login = $this->f3->get('SESSION.user_login');
        $logger = new \Log(\Logs::CREATE);
        $errors = [];
        // VALIDATE
        $v = new \Validator($this->f3->get('POST'));
            $v->rules([
                    'lengthBetween' => [
                        ['title', 4, 200],
                        ['slug', 4, 200]
                    ],
                    'regex' => [['title', '/^[a-zA-Zа-яА-Я0-9\s?!\.,:\']+$/u']],
                    'required' => ['title','slug'],
                    'slug' => ['slug']
            ]);
            if($v->validate()){
                $model = new \Page($this->database);
                $find_rec = $model -> find(array('slug = ?',$slug));
                if(isset($find_rec[0])){
                $errors[0]['find'] = ["result" => "slug уже существует"];
                    echo json_encode ($errors, JSON_FORCE_OBJECT);
                return;
                }
                $model->add();
                $logger->write("User " . $user_login . ' создал страницу');
                $errors[0]['status'] = ["result" => "OK"];
                echo json_encode ($errors, JSON_FORCE_OBJECT);
            }else{
                $errors[] = $v->errors();
                echo json_encode ($errors, JSON_FORCE_OBJECT);
            }
    }else{
        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
        $users = $this->database->exec('SELECT id, login FROM users');
        $model = new \Mediafile($this->database);
        $images = $model->fieldId('id');
        $this->f3->set('users', $users);
        $this->f3->set('images', $images);
        $this->f3->set('view', 'admin/templates/admin_page_create.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');
    }
}

function edit(){
    $this->checkForCSRFAttack();
    if($_POST){
        $user_login = $this->f3->get('SESSION.user_login');
        $logger = new \Log(\Logs::CREATE);
        $id = $this->f3->get('PARAMS.item');
        $errors = [];
        // VALIDATE
        $v = new \Validator($this->f3->get('POST'));
        $v->rules([
                'lengthBetween' => [['title', 4, 200]],
                'regex' => [['title', '/^[a-zA-Zа-яА-Я0-9\s?!\.,:\']+$/u']],
                'required' => ['title'],
        ]);
        if($v->validate()){
            $logger->write("User " . $user_login . ' Редактировал страницу ID = ' . $id);
            $model = new \Page($this->database);
            $model->edit($id);
            $errors[0]['status'] = ["result" => "OK"];
            echo json_encode ($errors, JSON_FORCE_OBJECT);
        }else{
            $errors[] = $v->errors();
            echo json_encode ($errors, JSON_FORCE_OBJECT);
        }
    }else{
        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
        $id = $this->f3->get('PARAMS.item');
        $model = new \Page($this->database);
        $page = $model->getByID($id);
        $users = $this->database->exec('SELECT id, login FROM users');
        $model = new \Mediafile($this->database);
        $images = $model->fieldId('id');
        $this->f3->set('page', $page);
        $this->f3->set('users', $users);
        $this->f3->set('images', $images);
        $this->f3->set('view', 'admin/templates/admin_page_edit.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');



    }
}
function delete(){
    $id = $this->f3->get('PARAMS.item');
    $user_login = $this->f3->get('SESSION.user_login');
    $model = new \Page($this->database);
    $model->delete($id);
    $logger = new \Log(\Logs::CREATE);
    $logger->write("User " . $user_login . ' Удалил страницу');
    $this->f3->reroute('@pagesall');


}


}/* END CLASS  */
