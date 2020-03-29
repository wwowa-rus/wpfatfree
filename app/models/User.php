<?php /* **********  Clacc Model USER PHP File  ********* */
class User extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'users');
    }

    /* *******   Получение всех записей из таблицы  array ***********/
    public function all() {
        $data = array();
        for ($this->load(); !$this->dry(); $this->next())
        $data[] = $this->cast();
        return $data;
    }
    /* ***********   Получение всех записей из таблицы  array ****************
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
    /*  выборка записи по id */
    public function getByID($id) {
        $data = array();
        $this->load(array('id=?', $id));
        $data[] = $this->cast();
        return $data;
    }
    /* Вставка записи через POST */
    public function add() {
        $this->copyFrom('POST');
        $this->save();
    }
    /* Вставка записи через массив */
    public function addArray($arr) {
        $this->copyFrom($arr);
        $this->save();
    }
    /* Редактирование записи через id witch array */
    public function editArray($id, $arr) {
        $this->load(array('id=?', $id));
        $this->copyFrom($arr);
        $this->update();
    }
    /* Редактирование записи через id witch POST */
    public function editPost($id) {
        $this->load(array('id=?', $id));
        $this->copyFrom('POST');
        $this->update();
    }
    /* Удаление записи через id */
    public function delete($id) {
        $this->load(array('id=?', $id));
        $this->erase();
    }
}