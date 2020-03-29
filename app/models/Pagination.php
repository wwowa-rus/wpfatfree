<?php  /*  class  Pagination  Model PHP FILE  */
class Pagination extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db, $limit) {
        parent::__construct($db, 'posts');
    $this->_limit   = $limit;
    }
    private $_limit;
    private $_page;
    private $_total;
    private $_count;
    public function getPost($page = 1) {
        $subset =[];
        $total_temp = 0;
        $this->_page    = $page;
            if ( $this->_limit == 'all' ) {
                for ($this->load(); !$this->dry(); $this->next())
                $subset[] = $this->cast();
            } else {
                $this->_total = $this->load()->count();
                $this->reset();
                for ($this->load(array(), array('order'=>'id DESC','offset'=>($page-1)*$this->_limit,'limit'=>$this->_limit)); !$this->dry(); $this->next()){
                $subset[] = $this->cast();
                $total_temp++;
                }
                $this->_count = ceil($this->_total/$this->_limit);
            }
            return  $subset;
    }

    public function createLinks( $links) {
        if ( $this->_limit == 'all' ) {
            return '';
        }
        $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
        $end        = ( ( $this->_page + $links ) < $this->_count ) ? $this->_page + $links : $this->_count;
        $html       = '<ul class="pagination">';
        $class      = ( $this->_page == 1 ) ? "disabled" : "";
        $html       .= '<li class="page-item ' . $class . '"><a href="/blog/1" class="page-link">&laquo;</a></li>';
            if ( $start > 1 ) {
            $html   .= '<li class="page-item"><a href="/blog/1" class="page-link">1</a></li>';
            $html   .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        for ( $i = $start ; $i <= $end; $i++ ) {
            $class  = ( $this->_page == $i ) ? "active" : "";
            $html   .= '<li class="page-item ' . $class .   '"><a href="/blog/' . $i  . '" class="page-link">' . $i . '</a></li>';
        }
        if ( $end < $this->_count ) {
            $html   .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            $html   .= '<li class="page-item"><a href="/blog/' . $this->_count . '" class="page-link">' . $this->_count . '</a></li>';
        }
        $class      = ( $this->_page == $this->_count ) ? "disabled" : "";
        $html       .= '<li class="page-item ' . $class . '"><a href="/blog/' . $this->_count . '" class="page-link">&raquo;</a></li>';
        $html       .= '</ul>';
        return $html;
    }
}   /* END CLASS */