<?php
class ContactController extends BaseController {

    public function render()
    {
        // Виджеты шапки и сайдбарв вставки
        $widget_ins =  $this->database->exec('SELECT value FROM widget ');
        $this->f3->set('widget_ins', $widget_ins );
        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
        $this->f3->set('view', '/theme/templates/contact.htm');
        $template = new \Template();
        echo $template->render('/theme/layout.htm');
    }
    public function sendcontact()
    {
        $this->checkForCSRFAttack();
        $errors = [];
        $attach_f = false;
		$dest_path = '';
        if (isset($_POST['mail_btn']) && $_POST['mail_btn'] == 'send_mail') {
            if (isset($_FILES['file_name']) && $_FILES['file_name']['error'] === UPLOAD_ERR_OK) {
            $attach_f = true;
        }
            $arr_valid = [
                    'name' => $_POST['name'],
                    'surname' => $_POST['surname'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone'],
                    'check' => $_POST['check'],
                    'message' => $_POST['message'],
                    'theme' =>  $_POST['theme']
                ];
        if($attach_f){
            $arr_valid['file_name'] = $_FILES['file_name']['name'];
            $arr_valid['file_size'] = $_FILES['file_name']['size'];
        }
        $v = new \Validator($arr_valid);
        $v->rules([
            'lengthBetween' => [
                ['name', 2, 20],
                ['surname', 2, 20],
                ['theme', 2, 200]
            ],
            'lengthMin' => [['message', 10]],
            'max' => [['file_size', 500000]],
            'required' => ['name', 'email', 'message', 'check', 'theme'],
            'regex' => [
                ['name', '/^[a-zA-Zа-яА-Я]+$/u'],
                ['surname', '/^[a-zA-Zа-яА-Я]+$/u'],
                ['phone', '/^[0-9]+[0-9-]+[0-9]+$/u'],
                ['file_name', '/^[\p{L}\s0-9-_+]+\.[A-Za-z0-9]{2,4}$/u'],
                ['theme', '/^[a-zA-Zа-яА-Я0-9\s?!\.,:\']+$/u']
            ],
            'accepted' => ['check'],
            'email' => [['email']],
        ]);
                if ($v->validate()){
                    if($attach_f){
                        $fileTmpPath = $_FILES['file_name']['tmp_name'];
                        $fileName = $_FILES['file_name']['name'];
                        $fileSize = $_FILES['file_name']['size'];
                        $fileType = $_FILES['file_name']['type'];
                        $fileNameCmps = explode(".", $fileName);
                        $fileExtension = strtolower(end($fileNameCmps));
                        // sanitize file-name
                        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                        // check if file has one of the following extensions
                        $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');
                            if (in_array($fileExtension, $allowedfileExtensions)) {
                                // directory in which the uploaded file will be moved
                                $uploadFileDir = 'tmp/temp/';
                                $dest_path = $uploadFileDir . $newFileName;
                                if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                                    \Flash::instance()->addMessage('image upload error');
                                    $this->f3->reroute('@contact');
                                }
                            } else {
                                \Flash::instance()->addMessage('Upload failed. Allowed file types: ' . implode(',',    $allowedfileExtensions));
                                $this->f3->reroute('@contact');
                            }
                    }
                    $st_mail = $this->f3->get('SMTP_EMAIL');
                    $s_port =  $this->f3->get('SMTP_PORT');
                    $s_ssl =   $this->f3->get('TLS_SSL');
                    $s_mail =  $this->f3->get('ADMIN_EMAIL');
                    $temp_pass =  $this->f3->get('SMTP_PASS');
					$mailer = '"' .  $arr_valid['name'] . '"' . '<'.  $arr_valid['email'] . '>' ;
                    $to_mail = '<'. $s_mail . '>';
                    $message = $arr_valid['message'];
					$crypt =  new \Crypt('smtp_pass');
                    $s_pass = $crypt ->DeCode($temp_pass);
$smtp = new \SMTP ( $st_mail, $s_port, $s_ssl, $s_mail, $s_pass);
$smtp->set('From', $to_mail);
$smtp->set('To', $to_mail);
$smtp->set('Subject', $arr_valid['theme']);
$smtp->set('Errors-to', $to_mail);
$smtp->set('Reply-To',  $mailer);
if($attach_f){$smtp->attach( $dest_path );}
$sent = $smtp->send($message, TRUE);
$logger = new \Log(\Logs::CREATE);
$mylog = $smtp->log();
$logger->write( $mylog); 
                   $errors[0]['status'] = ["result" => "OK"];
                    echo json_encode($errors, JSON_FORCE_OBJECT);
					  \Flash::instance()->addMessage('Сообщение успешно отправлено!!! ');
					 $this->f3->reroute('@contact');
                } else {
                    $errors = $v->errors();
                    if(!empty($errors)){
                        foreach($errors as $error => $val){
                            \Flash::instance()->addMessage('поле ' . $error .':');
                            foreach($val as $err)
                                \Flash::instance()->addMessage('Ошибка: ' . $err);
                        }
                    }
                    $this->f3->reroute('@contact');
                }
        }
    }
}
