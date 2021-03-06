<?php /* **********  Clacc Model BlogPost PHP File  ********* */
class Blogpost extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'posts');
    }
    /* **   Получение всех записей из таблицы  array **********/
    public function all() {
        $data = array();
        for ($this->load(); !$this->dry(); $this->next())
        $data[] = $this->cast();
        return $data;
    }
    /* *** Получение LIMIT записей из таблицы  array *********/
    public function allLimit($limit, $sort) {
        $data = array();
        for ($this->load(array(), array('order'=> $sort, 'limit'=> $limit)); !$this->dry(); $this->next())
        $data[] = $this->cast();
        return $data;
    }
    /* **** Получение LIMIT записей из таблицы  array **********/
    public function catLimit($cat, $sort, $limit) {
        $data = array();
        for ($this->load(array('cat_id=?', $cat), array('order'=> $sort, 'limit'=> $limit)); !$this->dry(); $this->next())
        $data[] = $this->cast();
        return $data;
    }
    /* ******   Получение всех записей из таблицы  array *************
    *** params $key Замена индексов массива на индексы поля $key = 'id' */
    public function fieldId($key) {
        $data = array();
        for ($this->load(array(), array('order'=>'id DESC')); !$this->dry(); $this->next())
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
    /*  Insert reccord  */
    public function add() {
        $this->copyFrom('POST');
        $this->save();
    }
    /*  Edit reccord ID  */
    public function edit($id) {
        $this->load(array('id=?', $id));
        $this->copyFrom('POST');
        $this->update();
    }
    /*  Delete reccord ID  */
    public function delete($id) {
        $this->load(array('id=?', $id));
        $this->erase();
    }
}
