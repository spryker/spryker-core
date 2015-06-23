<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;

interface SalesQueryContainerInterface
{

    /**
     * @param int $idSalesOrder
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesById($idSalesOrder);

    /**
     * @return SpySalesOrderQuery
     */
    public function querySales();

}
