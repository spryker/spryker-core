<?php

namespace SprykerFeature\Zed\Gui\Communication\Table;

class TableOptions
{

    /**
     * @var array
     */
    protected $tableClass = [
        'table',
        'table-stripped',
        'table-bordered',
        'table-hover',
        'gui-table-data',
    ];

    /**
     * @param array $classesArray
     */
    public function addClass(array $classesArray)
    {
        $this->tableClass = array_merge($this->tableClass, $classesArray);
    }

    /**
     * @return array
     */
    public function getTableClass()
    {
        return $this->tableClass;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

}
