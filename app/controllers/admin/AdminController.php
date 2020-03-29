<?php  /* AdminController php class file */
namespace admin;
use View;
use Logs;
class AdminController extends BaseAdminController
{
    /* Админка сайта */
    public function render()
    {
        $rec_count = [];
        $rec_count_temp = $this->database->exec('SELECT COUNT(*) FROM users');
        $rec_count['users'] = $rec_count_temp[0]['COUNT(*)'];
        $rec_count_temp = $this->database->exec('SELECT COUNT(*) FROM posts');
        $rec_count['posts'] = $rec_count_temp[0]['COUNT(*)'];
        $rec_count_temp = $this->database->exec('SELECT COUNT(*) FROM pages');
        $rec_count['pages'] = $rec_count_temp[0]['COUNT(*)'];
        $rec_count_temp = $this->database->exec('SELECT COUNT(*) FROM categories');
        $rec_count['cats'] = $rec_count_temp[0]['COUNT(*)'];
        //$rec_count_temp = $this->database->exec('SELECT COUNT(*) FROM wps_comments');
        //$rec_count['comments'] = $rec_count_temp[0]['COUNT(*)'];
        //$rec_count_temp = $this->database->exec('SELECT COUNT(*) FROM wps_images');
        //$rec_count['images'] = $rec_count_temp[0]['COUNT(*)'];
        //$rec_count_temp = $this->database->exec('SELECT COUNT(*) FROM wps_menu');
        //$rec_count['menu'] = $rec_count_temp[0]['COUNT(*)'];
        $this->f3->set('rec_count', $rec_count);
        $this->f3->set('view', 'admin/templates/admin_home.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');
        }














}
