<?php
namespace admin;

class MediafileController extends BaseAdminController
{

    public function all(){
        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
        $model = new \Mediafile($this->database);
        $images = $model->fieldId('id');
        $this->f3->set('images', $images);
        $this->f3->set('view', 'admin/templates/admin_media.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');
    }

    public function addimage(){
        $this->checkForCSRFAttack();
        $user_login = $this->f3->get('SESSION.user_login');
        $save_file = false;
        $data = array();
        if (isset($_FILES['name'])) {
            $save_file = false;
            $file_tmp = $_FILES['name']['tmp_name'];
            $file_name = $_FILES['name']['name'];
            $v = new \Validator(array('image' => array(
                'title' => $_POST['title'],
                'alt' => $_POST['alt'],
                'name' => $_FILES['name']['name'],
                'size' => $_FILES['name']['size'],
            )));
            $v->rules([
                'lengthBetween' => [
                    ['image.title', 4, 200],
                    ['image.alt', 4, 200],
                ],
                'required' => ['image.name'],
                'alphaNum' => ['image.alt'],
                'regex' => [['image.title', '/^[a-zA-Zа-яА-Я0-9\s?!\.,:\']+$/u']],
                'max' => [['image.size', 500000]],
            ]);
            if ($v->validate()) {
                $logger = new \Log(\Logs::CREATE);
                $imgSaveName = $this->renameImage($file_name);
                move_uploaded_file($file_tmp, "uploads/" . $imgSaveName);
                if($_POST['img_type'] == 0 ){
                $this->imageResize($imgSaveName);
                }
                $logger->write('Файл' . $imgSaveName .  'загружен в библиотеку изображений');
                $data['name'] = $imgSaveName;
                $save_file = true;
                // write base mysql
                if ($save_file && isset($_POST['add_image'])) {
                    $data['title'] = $_POST['title'];
                    $data['alt'] = $_POST['alt'];
                    $data['img_type'] = $_POST['img_type'];
                    $mediafile = new \Mediafile($this->database);
                    $mediafile->addArray($data);
                }
                // output errors validations
            } else {
                $errors = $v->errors();
                if (!empty($errors)) {
                    foreach ($errors as $error => $val) {
                        \Flash::instance()->addMessage('поле ' . $error . ':');
                        foreach ($val as $err) {
                            \Flash::instance()->addMessage('Ошибка: ' . $err);
                        }
                    }
                }
            }
        }
        $this->f3->reroute('@medialiball');
    }

    public function infoimage(){
        $this->checkForCSRFAttack();
        $logger = new \Log(\Logs::UPLOADS);
        $func_post = $this->f3->get('POST.func_info');
        $id = $this->f3->get('POST.img_id');
        $image_name = $this->f3->get('POST.img_name');
        if (isset($_POST['func_info'])) {
            $model = new \Mediafile($this->database);
            switch ($func_post) {
                case 'delete':
                    $img_type =  $model->delete($id);
                    $logger->write('ID = ' . $id . ": Успешно удален из базы");
                    unlink($this->f3->get('UPLOADS') . $image_name);
                    if($img_type == 0){
                        unlink($this->f3->get('UPLOADS') . 'thrumb_m/' . $image_name);
                        unlink($this->f3->get('UPLOADS') . 'thrumb_s/' . $image_name);
                    }
                    $logger->write('ID = ' . $id . ":  Успешно удален из папок");
                    $this->f3->reroute('@medialiball');
                    break;
                case 'update':
                    $v = new \Validator($this->f3->get('POST'));
                    $v->rules([
                        'lengthBetween' => [
                            ['title', 4, 200],
                            ['alt', 4, 200],
                        ],
                        'alphaNum' => ['alt'],
                        'regex' => [['title', '/^[a-zA-Zа-яА-Я0-9\s?!\.,:\']+$/u']],
                    ]);
                    if ($v->validate()) {
                        $model->edit($id);
                        $logger->write($image_name . "Успешно обновлен");
                        $this->f3->reroute('@medialiball');
                    } else {
                        $errors = $v->errors();
                        if (!empty($errors)) {
                            foreach ($errors as $error => $val) {
                                \Flash::instance()->addMessage('поле ' . $error . ':');
                                foreach ($val as $err) {
                                    \Flash::instance()->addMessage('Ошибка: ' . $err);
                                }
                            }
                        }
                    }
                    $this->f3->reroute('@medialiball');
                    break;
            }
        }
    }

    public function imageResize($img_name){
        $path = 'uploads/' . $img_name;
        $img = new \Image($path); // relative to UI search path
        $path_m = 'uploads/thrumb_m/' . $img_name;
        $path_s = 'uploads/thrumb_s/' . $img_name;
        $img->resize(300);
        $this->f3->write($path_m, $img->dump('jpeg'));
        $img->resize(100);
        $this->f3->write($path_s, $img->dump('jpeg'));
    }
    
    public function renameImage($img_name){
        $fileName_rename = $img_name;
        $fileName_path = "uploads/" . $fileName_rename;
        if (file_exists($fileName_path)) {
            $index = 1;
            while (file_exists($fileName_path)) {
                $file_body = explode('.', $fileName_rename)[0];
                $tmp = explode('.', $fileName_rename);
                $file_ext = end($tmp);
                $fileName_rename = $file_body . strval($index) . '.' . $file_ext;
                $fileName_path = "uploads/" . $fileName_rename;
                $index++;
            }
        }
        return $fileName_rename;
    }
} /* END CLASS */
