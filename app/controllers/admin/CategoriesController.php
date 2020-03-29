<?php
namespace admin;
class CategoriesController extends BaseAdminController {

    /*  Список всех категорий */
    function all() {
        $model = new \Categories($this->database);
        $cats = $model->fieldId('id');
        $this->f3->set('cats', $cats);
        $this->f3->set('view', 'admin/templates/admin_categories.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');
    }

    function create() {
        $this->checkForCSRFAttack();
        if($this->f3->exists('POST.cat_create')){
            $logger = new \Log(\Logs::CREATE);
            // setup var
            $login = $this->f3->get('SESSION.user_login');
            $slug = $this->f3->get('POST.slug');
            // VALIDATE
            $v = new \Validator($this->f3->get('POST'));
            $v->rules([
                    'lengthBetween' => [
                        ['name', 4, 200],
                        ['slug', 4, 200]
                    ],
                    'regex' => [['name', '/^[a-zA-Zа-яА-Я0-9\s?!\.,:\']+$/u']],
                    'required' => ['name','slug'],
                    'slug' => ['slug']
            ]);
            if($v->validate()){
                $model = new \Categories($this->database);
                $cat_slug = $model -> find(array('slug = ?', $slug));
                if(empty($cat_slug)){
                \Flash::instance()->addMessage('Категория добавлена', 'find');
                $categories = $model->add();
                $logger->write("User " . $login . ' Добавил категорию');
                }else{
                \Flash::instance()->addMessage('Слаг категории ' . $cat_slug[0]['slug'] .  ' существует', 'find');
                $this->f3->reroute('@catcreate');
            }
            $this->f3->reroute('@catsall');
            }else{
                $errors = $v->errors();
                if(!empty($errors)){
                    foreach($errors as $error => $val){
                        \Flash::instance()->addMessage('поле ' . $error .':');
                        foreach($val as $err)
                            \Flash::instance()->addMessage('Ошибка: ' . $err);
                    }
                }
                $this->f3->reroute('@catcreate');
            }
        }else{
            $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
            $this->f3->set('view', 'admin/templates/admin_category_create.htm');
            $template = new \Template();
            echo $template->render('admin/admin_layout.htm');
    }
}



function edit(){
    if($this->f3->exists('POST.edit_cat')){
    // VALIDATE
    $user_login = $this->f3->get('SESSION.user_login');
    $logger = new \Log(\Logs::CREATE);
    $cat_id = $this->f3->get('PARAMS.item');
    $slug = $this->f3->get('POST.slug');
    $v = new \Validator($this->f3->get('POST'));
    $v->rules([
        'lengthBetween' => [
            ['name', 4, 200],
            ['slug', 4, 200]
        ],
        'regex' => [['name', '/^[a-zA-Zа-яА-Я0-9\s?!\.,:\']+$/u']],
        'required' => ['name','slug'],
        'slug' => ['slug']
]);
    if($v->validate()){
        $model = new \Categories($this->database);
        $cat_slug = $model -> find(array('slug = ?', $slug));
        if(empty($cat_slug)){
            $model -> editPost($cat_id);
            $logger->write("User " . $user_login . ' Изменил категорию');
            $this->f3->reroute('@catsall');
        }else{
            \Flash::instance()->addMessage('Слаг категории ' . $cat_slug[0]['slug'] .  ' существует', 'find');
            $this->f3->reroute('@catedit');
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
        $this->f3->reroute($this->f3->get('@catedit'));
    }
    }else{
        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
        $id = $this->f3->get('PARAMS.item');
        $model = new \Categories($this->database);
        $cat = $model->getByID($id);
        $this->f3->set('cat', $cat);
        $this->f3->set('view', 'admin/templates/admin_category_edit.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');

    }
}
function delete() {
    $id = $this->f3->get('PARAMS.item');
    $user_login = $this->f3->get('SESSION.user_login');
    //$model_post = new \Blogpost($this->database);
    //$field = $model_post->select( 'id >= 1')->cast();
    $find_cat = $this->database->exec(
    'SELECT id FROM posts WHERE id>=? and cat_id = '. $id .' LIMIT 1','1');
    if(!empty($find_cat)){
        \Flash::instance()->addMessage('Удалите все посты этой категории');
    }else{
        $model = new \Categories($this->database);
        $model->delete($id);
        $logger = new \Log(\Logs::CREATE);
        $logger->write("User " . $user_login . ' Удалил категорию');   
    }
    $this->f3->reroute('@catsall');
}
}/* END Class */
