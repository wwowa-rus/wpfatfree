<?php /* **********  Clacc Model MEDIALIBS PHP File  ********* */
class Mediafile extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'images');
    }
    /* *******   Получение всех записей из таблицы  array ********/
    public function all() {
        $data = array();
        for ($this->load(); !$this->dry(); $this->next())
        $data[] = $this->cast();
        return $data;
    }
    /* ****** Получение всех записей из таблицы  array ****************
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
     /* Вставка записи через массив */
     public function addArray($arr) {
        $this->copyFrom($arr);
        $this->save();
    }
     /*  Edit reccord ID  */
     public function edit($id) {
        $this->load(array('id=?', $id));
        $this->copyFrom('POST');
        $this->update();
    }
    /*  Delete reccord ID return type img delete  */
    public function delete($id) {
        $this->load(array('id=?', $id));
        $img_type = $this->get('img_type');
        $this->erase();
    return $img_type;
    }
}
