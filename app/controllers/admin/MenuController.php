<?php
namespace admin;

class MenuController extends BaseAdminController
{

    public function all()
    {
        $modelPage = new \Page($this->database);
        $pages = $modelPage->all();
        $modelMenu = new \Menu($this->database);
        $menu = $modelMenu->all();
        $type = end($menu)['type'];
        $this->f3->set('type', $type);
        $this->f3->set('pages', $pages);
        $this->f3->set('menu', $menu);
        $this->f3->set('view', 'admin/templates/admin_menu.htm');
        $template = new \Template();
        echo $template->render('admin/admin_layout.htm');

    }
    function view_cat ($dataset) {

        $arr = "";
        foreach ($dataset as $menu) {

        $arr .= '<li id ="' . $menu['id']  . '" data-value = "' . $menu['value']  .'"><div>' . $menu['value'] . '</div>';
      //  $arr .= '<li id ="' . $menu['id']  .'"><div>' . $menu['value'] . '</div>';

        if(!empty($menu['childs'])) {
            $arr .= '<ul>';
                $arr .= $this->view_cat($menu['childs']);
              $arr .= '</ul>';
            }
           $arr .= '</li>';

        }
        return $arr;
    }
// ===============================================
    public function view_cat_printInline($dataset)
    {
        $arr = "";
        foreach ($dataset as $menu) {
            if (empty($menu['childs'])) {
                $link_page = explode('_', $menu['id'])[1];
                $arr .= '<li id ="' . $menu['id'] . '" class="nav-item">' . "\n";
                $arr .= '<a class="nav-link" href="{{ \'page\', \'item =' . $link_page  .   '\'| alias }}">' . $menu['value'] . "</a> \n";
            } else {
                $arr .= '<li id ="' . $menu['id'] . '" class="nav-item  dropdown">' . "\n";
                $arr .= '<a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown">' . $menu['value'] . "</a> \n";
            }

            if (!empty($menu['childs'])) {
                $arr .= '<div class="dropdown-menu">' . "\n";
                foreach ($menu['childs'] as $item) {
                    $link_page = explode('_', $item['id'])[1];
                    //$arr .= '<a class="dropdown-item" href="/page/' . $link_page . '">' . $item['value'] . '</a>';
                    //'country=Rhine'
                    $arr .= '<a class="dropdown-item" href="{{ \'page\', \'item =' . $link_page  .   '\'| alias }}">' . $item['value'] . '</a>';
                    $arr .= '<div class="dropdown-divider"></div>';
                }
                $arr .= "</div> \n";
            }
            $arr .= "</li> \n";
        }
        return $arr;
    }
// ===============================================
    public function getTree($dataset)
    {
        $tree = array();

        foreach ($dataset as $id => &$node) {
            //Если нет вложений
            if (!$node['parentId']) {
                $tree[$id] = &$node;
            } else {
                //Если есть потомки то перебераем массив
                $dataset[$node['parentId']]['childs'][$id] = &$node;
            }
        }
        return $tree;
    }
    public function menuconstruct()
    {
        //  $return_arr[] = array("message" => 'все в порядке');
        $form_data = json_decode($_POST['form_data']);
        $menu_data = $_POST['menu_data'];
        $post_switch = $form_data->switch_route;
        switch ($post_switch) {
            case 1:
                // Open menu iz BD
                $id = $form_data->id_menu_mame;
                $model = new \Menu($this->database);
                $menu_items = $model->getByID($id);
                $array_temp = json_decode($menu_items[0]['content'], true);
                $array_key = [];
                foreach ($array_temp as $item) {
                    $array_key[$item['id']] = $item;
                }
                $tree = $this->getTree($array_key);
                $menu = '<ul class="sTreeList listsClass" id="sTreeList">' . $this->view_cat($tree) . "</ul>";
                $menu_name = $form_data->menu_mame;
                $data_menu = [
                    "menu" => $menu,
                    "flag" => "1",
                    "menu_name" => $menu_name,
                ];
                echo json_encode($data_menu);
                break;
            case 2: //save new Empty Name menu in BD
                $menu_new_save = $form_data->menu_name_new;
                $menu_type = $form_data->menu_type;
                $v = new \Validator(array('name' => $menu_new_save));
                $v->rule( 'alphaNum', 'name');
                $v->rule( 'lengthBetween','name', 4, 20);
                if($v->validate()){
                    $model = new \Menu($this->database);
                    $menu_exist = $model->find(array('name = ?', $menu_new_save));
                    if ($menu_new_save && !$menu_exist) {
                        $array_temp = ['name' => $menu_new_save,
                                       'type' => $menu_type
                        ];
                        $model->addArray($array_temp);
                        $data_menu = [
                            "flag" => "2",
                            "message" => "Меню создано:имя " . " " . $menu_new_save,
                        ];
                        echo json_encode($data_menu);
                    } else {
                        $data_menu = [
                            "flag" => "2",
                            "message" => 'Введите имя меню или имя уже существует',
                        ];
                        echo json_encode($data_menu);
                    }
                }else{
                    $data_menu = [
                        "flag" => "2",
                        "message" => 'Имя меню должно состоять из букв и цифр 4-20',
                    ];
                    echo json_encode($data_menu);
                }
                break;
            case 3: // save item menu in BD
                $model = new \Menu($this->database);
                $id = $form_data->id_menu_mame;
                $menu_name = $form_data->menu_mame;
                if ($menu_data) {
                   // $json_menu = $_POST['menu_data'];
                    $bd_temp = ["content" => $menu_data];
                    $isset_rec = $model->find(array('id=?', $id));

                    if ($isset_rec) {

                        $model->editArray($id, $bd_temp);
                        $data_menu = [
                            "flag" => "3",
                            "message" => 'Меню изменено',
                        ];
                    } else {
                        $model_items->addArray($bd_temp);
                        $data_menu = [
                            "flag" => "3",
                            "message" => 'Создано новое меню',
                        ];
                    }
                    echo json_encode($data_menu);
                } else {
                    $data_menu = [
                        "flag" => "3",
                        "message" => 'Не удалось сохранить меню',
                    ];

                    $this->database->exec('DELETE FROM wps_menuitem WHERE menu_id = ?', $menu_id);
                    echo json_encode($data_menu);
                }
                break;

            case 4: //create menu in file
                $model = new \Menu($this->database);
                $id = $form_data->id_menu_mame;
                $name = $form_data->menu_mame;
                $menu_blog = $form_data->menu_blog;
                $menu_contact = $form_data->menu_contact;
                $menuitem = $model->getByID($id);
                $array_temp = json_decode($menuitem[0]['content'], true);
                $array_key = [];
                foreach ($array_temp as $item) {
                    $array_key[$item['id']] = $item;
                }
                $tree = $this->getTree($array_key);
                $menu = "<!-- **** Navbar php File **** --> \n";
                $menu .= '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">' . "\n";
                $menu .= '<a class="navbar-brand" href="{{ @BASE }}{{\'frontpage\'| alias }}"><i class="fas fa-house-damage"></i></a>' . "\n";
                $menu .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav_content">' . "\n";
                $menu .= '<span class="navbar-toggler-icon"></span>' . "\n";
                $menu .= '</button>' . "\n";
                $menu .= '<div class="collapse navbar-collapse" id="nav_content">' . "\n";
                $menu .= '<ul class="navbar-nav mr-auto">' . "\n";
                if(isset($menu_blog)){
                    $menu .= '<li  class="nav-item">' . "\n";
                    $menu .= '<a class="nav-link" href="{{ \'blog\', \'item = 1 \'| alias }}">{{@blog}}</a>' . "\n";
                    $menu .= '</li>' . "\n";
                }
                $menu .= $this->view_cat_printInline($tree);
                if(isset($menu_contact)){
                    $menu .= '<li  class="nav-item">' . "\n";
                    $menu .= '<a class="nav-link" href="{{ @BASE }}{{ \'contact\'| alias }}">contact</a>' . "\n";
                    $menu .= '</li>' . "\n";
                }
                $menu .= '</ul>' . "\n";
                $menu .= '<form class="form-inline my-2 my-lg-0">' . "\n";
                $menu .= '<input class="form-control form-control-sm mr-sm-2" type="search" placeholder="{{ @search }}">' . "\n";
                $menu .= '<button class="btn btn-sm btn-outline-success my-2 my-sm-0" type="submit">{{ @search }}</button>' . "\n";
                $menu .= '</form>' . "\n";
                $menu .= '</div></nav>' . "\n";
                $file_path = 'views/theme/navbar.htm';
                $dfdf = file_put_contents($file_path, $menu);
                $data_menu = [
                    "flag" => "3",
                    "message" => 'Mеню записано в файл',
                ];
                echo json_encode($data_menu);
                break;
        } // switch
    }
}
