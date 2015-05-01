<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid\Comment;

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
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery
     */
    protected function getQuery()
    {
        $query = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderCommentQuery::create();
        if (null !== $this->idSalesOrder) {
            $query->filterByFkSalesOrder($this->idSalesOrder);
        }

        return $query;
    }

}
