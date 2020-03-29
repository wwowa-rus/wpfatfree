<?php  /* **********  Clacc Model MENU PHP File  ********* */
class Menu extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'menu');
    }
    /* *******   Получение всех записей из таблицы  array **********/
    public function all() {
        $data = array();
        for ($this->load(); !$this->dry(); $this->next())
        $data[] = $this->cast();
        return $data;
    }
    /*  выборка записи по id */
    public function getByID($id) {
        $data = array();
        $this->load(array('id=?', $id));
        $data[] = $this->cast();
        return $data;
    }
    /* Вставка записи через массив */
    public function addArray($arr) {
        $this->copyFrom($arr);
        $this->save();
    }
    /*  Edit reccord ID  */
    public function editArray($id, $array) {
        $this->load(array('id=?', $id));
        $this->copyFrom($array);
        $this->update();
    }
}
