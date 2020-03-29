<?php
class ErrorController extends BaseController {

    function errorpage() {


        $this->f3->set('view', '/theme/error.htm');
        $template = new \Template();
        echo $template->render('/theme/layout.htm');


    }






}
