<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiOrdersAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderResourceCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiConfig;

class SalesOrdersResourceMapper implements SalesOrdersResourceMapperInterface
{
    /**
     * @var list<\Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\ApiOrdersAttributesMapperPluginInterface>
     */
    protected array $apiOrdersAttributesMapperPlugins;

    /**
     * @param list<\Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\ApiOrdersAttributesMapperPluginInterface> $apiOrdersAttributesMapperPlugins
     */
    public function __construct(array $apiOrdersAttributesMapperPlugins)
    {
        $this->apiOrdersAttributesMapperPlugins = $apiOrdersAttributesMapperPlugins;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer> $apiOrdersAttributesTransfers
     * @param \Generated\Shared\Transfer\OrderResourceCollectionTransfer $orderResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderResourceCollectionTransfer
     */
    public function mapApiOrdersAttributesTransfersToOrderResourceCollectionTransfer(
        array $apiOrdersAttributesTransfers,
        OrderResourceCollectionTransfer $orderResourceCollectionTransfer
    ): OrderResourceCollectionTransfer {
        foreach ($apiOrdersAttributesTransfers as $apiOrdersAttributesTransfer) {
            $glueResourceTransfer = $this->mapApiOrdersAttributesTransferToGlueResourceTransfer(
                $apiOrdersAttributesTransfer,
                new GlueResourceTransfer(),
            );

            $orderResourceCollectionTransfer->addOrderResource($glueResourceTransfer);
        }

        return $orderResourceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer>
     */
    public function mapOrderListTransferToApiOrdersAttributesTransfers(OrderListTransfer $orderListTransfer): array
    {
        $apiOrdersAttributesTransfers = [];
        $orderTransfers = $orderListTransfer->getOrders();
        foreach ($orderTransfers as $orderTransfer) {
            $apiOrdersAttributesTransfers[] = $this->mapOrderTransferToApiOrdersAttributesTransfer(
                $orderTransfer,
                new ApiOrdersAttributesTransfer(),
            );
        }

        return $this->executeApiOrdersAttributesExpanderPlugins($orderTransfers->getArrayCopy(), $apiOrdersAttributesTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiOrdersAttributesTransfer $apiOrdersAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapApiOrdersAttributesTransferToGlueResourceTransfer(
        ApiOrdersAttributesTransfer $apiOrdersAttributesTransfer,
        GlueResourceTransfer $glueResourceTransfer
    ): GlueResourceTransfer {
        return $glueResourceTransfer
            ->setType(SalesOrdersBackendApiConfig::RESOURCE_SALES_ORDERS)
            ->setId($apiOrdersAttributesTransfer->getOrderReferenceOrFail())
            ->setAttributes($apiOrdersAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ApiOrdersAttributesTransfer $apiOrdersAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiOrdersAttributesTransfer
     */
    protected function mapOrderTransferToApiOrdersAttributesTransfer(
        OrderTransfer $orderTransfer,
        ApiOrdersAttributesTransfer $apiOrdersAttributesTransfer
    ): ApiOrdersAttributesTransfer {
        return $apiOrdersAttributesTransfer->fromArray($orderTransfer->toArray(), true);
    }

    /**
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer> $apiOrdersAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer>
     */
    protected function executeApiOrdersAttributesExpanderPlugins(
        array $orderTransfers,
        array $apiOrdersAttributesTransfers
    ): array {
        foreach ($this->apiOrdersAttributesMapperPlugins as $apiOrdersAttributesMapperPlugin) {
            $apiOrdersAttributesTransfers = $apiOrdersAttributesMapperPlugin->mapOrderTransfersToApiOrdersAttributesTransfer(
                $orderTransfers,
                $apiOrdersAttributesTransfers,
            );
        }

        return $apiOrdersAttributesTransfers;
    }
}
