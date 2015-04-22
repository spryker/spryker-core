<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid\Note;

use ModelCriteria;

class DataSource
{

    /** @var int */
    protected $idSalesOrder;

    /**
     * @param int|null $idSalesOrder
     */
    public function __construct($idSalesOrder = null)
    {
        $this->idSalesOrder = $idSalesOrder;
    }

    /**
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderNoteQuery
     */
    protected function getQuery()
    {
        $query = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderNoteQuery::create();
        if (null !== $this->idSalesOrder) {
            $query->filterByFkSalesOrder($this->idSalesOrder);
        }

        return $query;
    }

    /**
     * @return array
     */
    public function getAclUserOptions()
    {
        $collection = \SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclUserQuery::create()
            ->orderByIdAclUser(ModelCriteria::DESC)
            ->find();
        $options[] = ['value' => null, 'text' => 'NULL'];
        foreach ($collection as $item) {
            $options[] = ['value' => $item->getPrimaryKey(), 'text' => $item->getUsername()];
        }

        return $options;
    }

}
