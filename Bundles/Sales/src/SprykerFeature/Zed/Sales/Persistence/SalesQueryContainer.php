<?php

namespace SprykerFeature\Zed\Sales\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;

class SalesQueryContainer extends AbstractQueryContainer implements SalesQueryContainerInterface
{

    /**
     * @param string $url
     *
     * @return SpyUrl
     */
    public function querySales()
    {
        $query = SpySalesOrderQuery::create();

        return $query;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return SpySalesQuery
     */
    public function querySalesById($idSalesOrder)
    {
        $query = SpySalesOrderQuery::create();
        $query->filterByIdSalesOrder($idSalesOrder);

        return $query;
    }
}
