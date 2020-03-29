<?php /* ******TestController INFO LINK ASSETS php file*** */
namespace admin;
use BaseController;

class QuickRefController extends BaseController {

    function beforeroute() {
        //$this->checkForCSRFAttack();
        //$this->checkMenuItems();
        $this->checkLogin();
    }
    function render() {
        $this->f3->set('view', 'admin/templates/quick_ref.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');
        }
}
