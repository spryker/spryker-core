<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderResourceCollectionTransfer;
use Spryker\Glue\SalesOrdersBackendApi\Dependency\Facade\SalesOrdersBackendApiToSalesFacadeInterface;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper\SalesOrdersResourceMapperInterface;

class SalesOrdersResourceReader implements SalesOrdersResourceReaderInterface
{
    /**
     * @var \Spryker\Glue\SalesOrdersBackendApi\Dependency\Facade\SalesOrdersBackendApiToSalesFacadeInterface
     */
    protected SalesOrdersBackendApiToSalesFacadeInterface $salesFacade;

    /**
     * @var \Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper\SalesOrdersResourceMapperInterface
     */
    protected SalesOrdersResourceMapperInterface $salesOrdersResourceMapper;

    /**
     * @param \Spryker\Glue\SalesOrdersBackendApi\Dependency\Facade\SalesOrdersBackendApiToSalesFacadeInterface $salesFacade
     * @param \Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper\SalesOrdersResourceMapperInterface $salesOrdersResourceMapper
     */
    public function __construct(
        SalesOrdersBackendApiToSalesFacadeInterface $salesFacade,
        SalesOrdersResourceMapperInterface $salesOrdersResourceMapper
    ) {
        $this->salesFacade = $salesFacade;
        $this->salesOrdersResourceMapper = $salesOrdersResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderResourceCollectionTransfer
     */
    public function getOrderResourceCollection(OrderListTransfer $orderListTransfer): OrderResourceCollectionTransfer
    {
        $orderListTransfer = $this->salesFacade->searchOrders($orderListTransfer);

        $ordersBackendApiAttributesTransfers = $this->salesOrdersResourceMapper->mapOrderListTransferToOrdersBackendApiAttributesTransfers($orderListTransfer);
        $orderResourceCollectionTransfer = $this->salesOrdersResourceMapper->mapOrdersBackendApiAttributesTransfersToOrderResourceCollectionTransfer(
            $ordersBackendApiAttributesTransfers,
            new OrderResourceCollectionTransfer(),
        );

        return $orderResourceCollectionTransfer->setOrders($orderListTransfer->getOrders());
    }
}
