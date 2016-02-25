<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Sales\Business\Model\Order;

interface OrderReaderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return array|string[]
     */
    public function getDistinctOrderStates($idSalesOrder);
}
