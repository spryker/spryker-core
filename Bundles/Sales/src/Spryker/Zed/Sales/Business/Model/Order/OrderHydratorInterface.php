<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Sales\Business\Model\Order;

interface OrderHydratorInterface
{
    /**
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderTransferFromPersistenceByIdSalesOrder($idSalesOrder);
}
