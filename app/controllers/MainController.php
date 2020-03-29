<?php
class MainController extends BaseController {
    /**     * This renders *the* main  site.     */

    function beforeroute() {
        $keyPage = $this->f3->get('PARAMS.item');
        if(isset($keyPage)){
            $valid_item = filter_var($keyPage, FILTER_VALIDATE_INT,
            array('options' => array('min_range' => 1)));
            if(!$valid_item)
                $this->f3->error(404, 'page not found');
        }
    }
    function frontpage() {
       /* ********** WIDGET SLIDER  *************** */
        if($this->f3->get('WSLID_SHOW')){
            if($this->f3->get('WSLID_ALLCAT')){
                $model_wslid = new \Blogpost($this->database);
                $model_wslid->author = 'SELECT login FROM users '. 'WHERE users.id = posts.user_id';
                $model_wslid->category = 'SELECT name FROM categories '. 'WHERE categories.id = posts.cat_id';
                $blogposts_wslid = $model_wslid->allLimit(5, 'id DESC');
            }else{
                $model_wslid = new \Blogpost($this->database);
                $model_wslid->author = 'SELECT login FROM users '. 'WHERE users.id = posts.user_id';
                $model_wslid->category = 'SELECT name FROM categories '. 'WHERE categories.id = posts.cat_id';
                $blogposts_wslid = $model_wslid->catLimit($this->f3->get('WSLID_CAT'), 'id DESC', 5);
            }
            foreach($blogposts_wslid as &$blogpost){
                $exerpt =  strip_tags(mb_strimwidth($blogpost['content'], 0 , $this->f3->get('WSLID_EX'),'{...} '));
                $blogpost['content'] = $exerpt;
            }
            $this->f3->set('blogposts_wslid', $blogposts_wslid);

        }
 /* ********** WIDGET TOO BLOCK POST CATEGORY  *************** */
    if($this->f3->get('WSCAT_SHOW')){
        $model_wscat1 = new \Blogpost($this->database);
        $model_wscat1->author = 'SELECT login FROM users '. 'WHERE users.id = posts.user_id';
        $model_wscat1->category = 'SELECT name FROM categories '. 'WHERE categories.id = posts.cat_id';
        $blogposts_wscat1 = $model_wscat1->catLimit($this->f3->get('WSCAT1'), 'id DESC', 4);

        $model_wscat2 = new \Blogpost($this->database);
        $model_wscat2->author = 'SELECT login FROM users '. 'WHERE users.id = posts.user_id';
        $model_wscat2->category = 'SELECT name FROM categories '. 'WHERE categories.id = posts.cat_id';
        $blogposts_wscat2 = $model_wscat2->catLimit($this->f3->get('WSCAT2'), 'id DESC', 4);

        foreach($blogposts_wscat1 as &$blogpost){
            $exerpt =  strip_tags(mb_strimwidth($blogpost['content'], 0 , $this->f3->get('WSLID_EX'),'{...} '));
            $blogpost['content'] = $exerpt;
        }
        foreach($blogposts_wscat2 as &$blogpost){
            $exerpt =  strip_tags(mb_strimwidth($blogpost['content'], 0 , $this->f3->get('WSLID_EX'),'{...} '));
            $blogpost['content'] = $exerpt;
        }
        $this->f3->set('blogposts_wscat1', $blogposts_wscat1);
        $this->f3->set('blogposts_wscat2', $blogposts_wscat2);
    }
 /* ********** WIDGET ARCHIVE POST CATEGORY  *************** */
        if($this->f3->get('WREC_SHOW')){
            if($this->f3->get('WREC_ALLCAT')){
                $model_wrec = new \Blogpost($this->database);
                $model_wrec->author = 'SELECT login FROM users '. 'WHERE users.id = posts.user_id';
                $model_wrec->category = 'SELECT name FROM categories '. 'WHERE categories.id = posts.cat_id';
                $blogposts_wrec = $model_wrec->allLimit(7, 'id ASC');
            }else{
                $model_wrec = new \Blogpost($this->database);
                $model_wrec->author = 'SELECT login FROM users '. 'WHERE users.id = posts.user_id';
                $model_wrec->category = 'SELECT name FROM categories '. 'WHERE categories.id = posts.cat_id';
                $blogposts_wrec = $model_wrec->catLimit(7, $this->f3->get('WREC_CAT'));
            }
            foreach($blogposts_wrec as &$blogpost){
                $exerpt =  strip_tags(mb_strimwidth($blogpost['content'], 0 , $this->f3->get('WREC_EX'),'{...} '));
                $blogpost['content'] = $exerpt;
            }
            $this->f3->set('blogposts_wrec', $blogposts_wrec);
        }

        // -----------------------------------------------------------------------------------
        // Виджеты шапки и сайдбарв вставки
        $widget_ins =  $this->database->exec('SELECT value FROM widget ');
        $this->f3->set('widget_ins', $widget_ins );
        $this->f3->set('view', '/theme/templates/content_home.htm');
        $template = new Template();
        echo $template->render('/theme/home.htm');


     }

    function homerender() {
        $case = $this->f3->get('IS_HOME');
        if($case  == 'home'){
           $this->f3->reroute('@frontpage');
        }else{
            $this->f3->reroute('@blog(@item=1)');
        }
    }
    function render() {
        $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
        $limit = $this->f3->get('POST_MATCH');
        $limit = 8;// set limit config not
        $postCount = '';// кол-во записей
        $currentPage = 1; //текущий пост
        /* определение текущей страницы   */
        $keyPage = $this->f3->get('PARAMS.item');
        if(isset($keyPage)){
            $currentPage = $this->f3->get('PARAMS.item');
        }
        $fields = array('id', 'user_id', 'cat_id', 'title', 'date', 'thrumb', 'content');
        $model = new Pagination($this->database, $limit, $fields, 0 );
        $model->author = 'SELECT login FROM users '. ' WHERE posts.user_id = users.id';
        $model->cat= 'SELECT name FROM categories ' . ' WHERE posts.cat_id = categories.id';
        $post_subset = $model->getPost($currentPage);
        if(empty($post_subset)){
            $this->f3->error(404, 'page not found');
        }
        foreach($post_subset as &$subset){
            $exerpt =  strip_tags(mb_strimwidth($subset['content'], 0 , 250,'[...]'));
            $subset['content'] = $exerpt;
        }
       // Виджеты шапки и сайдбарв вставки
        $widget_ins =  $this->database->exec('SELECT value FROM widget ');
        $this->f3->set('widget_ins', $widget_ins );
        $pag =  $model->createLinks( '1');
        $this->f3->set('articleList', $post_subset);
        $this->f3->set('pag', $pag);
        $this->f3->set('view', '/theme/templates/content.htm');
        $template = new Template();
        echo $template->render('/theme/layout.htm');
    }

    function renderPost() {
        $id = $this->f3->get('PARAMS.item');
        $fields = array('id', 'user_id', 'cat_id', 'title', 'date', 'thrumb', 'content');
        $model = new Blogpost($this->database, $fields, 0);
        $model->author = 'SELECT login FROM users '. ' WHERE posts.user_id = users.id';
        $model->cat= 'SELECT name FROM categories ' . ' WHERE posts.cat_id = categories.id';
        $post = $model->getByID($id);
        if(is_null($post[0]['id'])){
            $this->f3->error(404, 'Page not found');
        }
        // Виджеты шапки и сайдбарв вставки
        $widget_ins =  $this->database->exec('SELECT value FROM widget ');
        $this->f3->set('widget_ins', $widget_ins );
        $this->f3->set('blogpost', $post);
        $this->f3->set('view', '/theme/templates/content_single.htm');
        $template = new Template();
        echo $template->render('/theme/layout.htm');
    }

    function renderPage() {
        $id = $this->f3->get('PARAMS.item');
        $fields = array('id', 'user_id', 'title', 'date', 'thrumb', 'content');
        $model = new Page($this->database, $fields, 0);
        $model->author = 'SELECT login FROM users '. ' WHERE pages.user_id = users.id';
        $page = $model->getByID($id);
        if(is_null($page[0]['id'])){
            $this->f3->error(404, 'Page not found');
        }
        // Виджеты шапки и сайдбарв вставки
        $widget_ins =  $this->database->exec('SELECT value FROM widget ');
        $this->f3->set('widget_ins', $widget_ins );
        $this->f3->set('page', $page);
        $this->f3->set('view', '/theme/templates/content_page.htm');
        $template = new Template();
        echo $template->render('/theme/layout.htm');
    }
}
