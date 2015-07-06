<?php
/**
 * Created by PhpStorm.
 * User: dsavin
 * Date: 02.07.15
 * Time: 19:01
 */
namespace SprykerFeature\Zed\UiExample\Communication\Table;

class UiExampleTable extends BaseTable {
    /**
     * @var array
     */
    protected $data;

    public function __construct($data){

        parent::__construct();
        $this->prepareDate($data);
    }

    public function prepareDate($data) {
        $this->loadData($data);
    }


    
}
