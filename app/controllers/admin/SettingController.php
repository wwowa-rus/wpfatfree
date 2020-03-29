<?php
namespace admin;

class SettingController extends BaseAdminController{
    public function all(){
        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
        $cats = $this->database->exec('SELECT id, name FROM categories ');
        $widget =  $this->database->exec('SELECT * FROM widget ');
        $model = new \Setting($this->database);
        $setting = $model->fieldId('name');
        $this->f3->set('widget', $widget);
        $this->f3->set('cats', $cats);
        $this->f3->set('setting', $setting);
        $this->f3->set('view', 'admin/templates/admin_setting.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');
    }
    public function settingsave(){
        $this->checkForCSRFAttack();
        $user_login = $this->f3->get('SESSION.user_login');
        if ($this->f3->VERB == 'POST') {
            $arr_post = $this->f3->get('POST');
            unset($arr_post['token']);
            unset($arr_post['setting']);
            $logger = new \Log(\Logs::CREATE);
            $v = new \Validator($arr_post);
            if($arr_post['save_smtpass'] == 'on'){
                    $v->rules([
                    'regex' => [['smtp_pass', '/^[A-Za-z0-9;_!#@]+$/u']],
                    'required' => ['smtp_pass'],
                    'lengthBetween' =>  [['smtp_pass', 4, 15]]
                    ]);
            }else{
                $arr_post['smtp_pass'] = $this->f3->get('SMTP_PASS');
                unset($arr_post['save_smtpass']);
            }
            $v->rules([
                'lengthBetween' => [
                    ['blogname', 4, 200],
                    ['blogdescription', 4, 200],
                    ['keywords', 4, 200],
                    ['title', 4, 200],
                    ['description', 4, 200],
                ],
                'email' => [
                    ['admin_email'],
                ],
                'integer' => ['smtp_port'],
                'regex' => [
                    ['blogname', '/^([a-zA-Zа-яА-Я0-9\s?!\.,])+$/u'],
                    ['blogdescription', '/^([a-zA-Zа-яА-Я0-9\s?!\.,])+$/u'],
                    ['keywords', '/^([a-zA-Zа-яА-Я0-9\s])+$/u'],
                    ['title', '/^([a-zA-Zа-яА-Я0-9\s?!\.,])+$/u'],
                    ['description', '/^([a-zA-Zа-яА-Я0-9\s?!\.,])+$/u'],
                    ['smtp_pass', '/^[A-Za-z0-9;_!#@]+$/u'],
                    ['smtp_email', '/^(smtp)\.[a-zA-Z0-9-]+\.[a-z]{2,3}$/u']
                ],
            ]);
            if ($v->validate()) {
                if($arr_post['save_smtpass'] == 'on'){
                    unset($arr_post['save_smtpass']);
                    $crypt =  new \Crypt('smtp_pass');
                    $temp_cr = $crypt ->soCode ($arr_post['smtp_pass']);
                    $arr_post['smtp_pass'] =  $temp_cr;
                }

                $model = new \Setting($this->database);
                $model->MultiRecUpdate($arr_post);
                $str_conf = "[globals] \n";
                foreach($arr_post as $key => $value){
                    $str_conf .= mb_strtoupper($key) .' = "'.  $value ."\" \n";
                }
                $fileName_path = "../tuning/conf_blog.ini";
                file_put_contents($fileName_path, $str_conf);
                $logger->write("User " . $user_login . " - Изменен файл конфигурации.");
                $this->f3->reroute('@settingall');
            } else {
                // errors
                $logger->write("User " . $user_login . " - неудачная попытка изменить настройки.");
                $errors = $v->errors();
                if (!empty($errors)) {
                    foreach ($errors as $error => $val) {
                        \Flash::instance()->addMessage('поле ' . $error . ':');
                            foreach ($val as $err) {
                            \Flash::instance()->addMessage('Ошибка: ' . $err);
                        }
                    }
                }
                $this->f3->reroute('@settingall');
            }
        }
    }

    public function widgetsave(){
        $arr_post = $this->f3->get('POST');
        unset($arr_post['token']);
        $model = new \Widget($this->database);
        $model->MultiRecUpdate($arr_post) ;
        $this->f3->reroute('@settingall');
        }
}
