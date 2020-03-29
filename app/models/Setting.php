<?php /* **********  Clacc Model SETTING PHP File  ********* */
class Setting extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'setting');
    }
    /* *******   Получение всех записей из таблицы  array ***********/
    public function all() {
        $data = array();
        for ($this->load(); !$this->dry(); $this->next())
        $data[] = $this->cast();
        return $data;
    }
     /* ***********   Получение всех записей из таблицы  array **************
    *** params $key Замена индексов массива на индексы поля $key = 'id' */
    public function fieldId($key) {
        $data = array();
        for ($this->load(array()); !$this->dry(); $this->next())
            $data[] = $this->cast();
        $dataKey = array();
        foreach ($data as $k => $v) {
                $dataKey[$v[$key]] = $v;
                unset($dataKey[$v[$key]][$key]);
        }
        $data = $dataKey;
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