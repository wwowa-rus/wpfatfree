<?php /* **********  Clacc Model WIDGET PHP File  ********* */
class Widget extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'widget');
    }
    /* ****  Получение всех записей из таблицы  array *********/
    public function all() {
        $data = array();
        for ($this->load(); !$this->dry(); $this->next())
        $data[] = $this->cast();
        return $data;
    }
    public function MultiRecUpdate($arr) {
        for ($this->load(array()); !$this->dry(); $this->next()){
            $field_name = $this->get('name');
            $this->copyFrom(array('value'=> $arr[$field_name]));
            $this->update();
        }
    }
}