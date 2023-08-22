<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderResourceCollectionTransfer;
use Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiConfig;

class SalesOrdersResourceMapper implements SalesOrdersResourceMapperInterface
{
    /**
     * @var list<\Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\OrdersBackendApiAttributesMapperPluginInterface>
     */
    protected array $ordersBackendApiAttributesMapperPlugins;

    /**
     * @param list<\Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\OrdersBackendApiAttributesMapperPluginInterface> $ordersBackendApiAttributesMapperPlugins
     */
    public function __construct(array $ordersBackendApiAttributesMapperPlugins)
    {
        $this->ordersBackendApiAttributesMapperPlugins = $ordersBackendApiAttributesMapperPlugins;
    }

    /**
     * @param list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer> $ordersBackendApiAttributesTransfers
     * @param \Generated\Shared\Transfer\OrderResourceCollectionTransfer $orderResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderResourceCollectionTransfer
     */
    public function mapOrdersBackendApiAttributesTransfersToOrderResourceCollectionTransfer(
        array $ordersBackendApiAttributesTransfers,
        OrderResourceCollectionTransfer $orderResourceCollectionTransfer
    ): OrderResourceCollectionTransfer {
        foreach ($ordersBackendApiAttributesTransfers as $ordersBackendApiAttributesTransfer) {
            $glueResourceTransfer = $this->mapOrdersBackendApiAttributesTransferToGlueResourceTransfer(
                $ordersBackendApiAttributesTransfer,
                new GlueResourceTransfer(),
            );

            $orderResourceCollectionTransfer->addOrderResource($glueResourceTransfer);
        }

        return $orderResourceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer>
     */
    public function mapOrderListTransferToOrdersBackendApiAttributesTransfers(OrderListTransfer $orderListTransfer): array
    {
        $ordersBackendApiAttributesTransfers = [];
        $orderTransfers = $orderListTransfer->getOrders();
        foreach ($orderTransfers as $orderTransfer) {
            $ordersBackendApiAttributesTransfers[] = $this->mapOrderTransferToOrdersBackendApiAttributesTransfer(
                $orderTransfer,
                new OrdersBackendApiAttributesTransfer(),
            );
        }

        return $this->executeOrdersBackendApiAttributesMapperPlugins($orderTransfers->getArrayCopy(), $ordersBackendApiAttributesTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer $ordersBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapOrdersBackendApiAttributesTransferToGlueResourceTransfer(
        OrdersBackendApiAttributesTransfer $ordersBackendApiAttributesTransfer,
        GlueResourceTransfer $glueResourceTransfer
    ): GlueResourceTransfer {
        return $glueResourceTransfer
            ->setType(SalesOrdersBackendApiConfig::RESOURCE_SALES_ORDERS)
            ->setId($ordersBackendApiAttributesTransfer->getOrderReferenceOrFail())
            ->setAttributes($ordersBackendApiAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer $ordersBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer
     */
    protected function mapOrderTransferToOrdersBackendApiAttributesTransfer(
        OrderTransfer $orderTransfer,
        OrdersBackendApiAttributesTransfer $ordersBackendApiAttributesTransfer
    ): OrdersBackendApiAttributesTransfer {
        return $ordersBackendApiAttributesTransfer->fromArray($orderTransfer->toArray(), true);
    }

    /**
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer> $ordersBackendApiAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer>
     */
    protected function executeOrdersBackendApiAttributesMapperPlugins(
        array $orderTransfers,
        array $ordersBackendApiAttributesTransfers
    ): array {
        foreach ($this->ordersBackendApiAttributesMapperPlugins as $ordersBackendApiAttributesMapperPlugin) {
            $ordersBackendApiAttributesTransfers = $ordersBackendApiAttributesMapperPlugin->mapOrderTransfersToOrdersBackendApiAttributesTransfers(
                $orderTransfers,
                $ordersBackendApiAttributesTransfers,
            );
        }

        return $ordersBackendApiAttributesTransfers;
    }
}
