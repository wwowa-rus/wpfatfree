<?php
namespace admin;
use BaseController;
class BaseAdminController extends BaseController {
    function beforeroute() {
        //$this->checkForCSRFAttack();
        //$this->checkMenuItems();
        $this->checkLogin();
    }


}
