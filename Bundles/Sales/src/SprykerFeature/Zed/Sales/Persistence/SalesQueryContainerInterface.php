<?php

namespace SprykerFeature\Zed\Sales\Persistence;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpyRedirectQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpyUrlQuery;

interface SalesQueryContainerInterface
{

    /**
     * @param int $idSalesOrder
     *
     * @return SpyUrlQuery
     */
    public function querySalesById($idSalesOrder);

    /**
     * @return SpyUrlQuery
     */
    public function querySales();

}
