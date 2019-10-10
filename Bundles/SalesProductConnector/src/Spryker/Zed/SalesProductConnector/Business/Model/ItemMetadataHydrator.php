<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Model;

use Generated\Shared\Transfer\ItemMetadataTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface;

class ItemMetadataHydrator implements ItemMetadataHydratorInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface
     */
    protected $salesProductConnectorQueryContainer;

    /**
     * @param \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface $SalesProductConnectorQueryContainer
     */
    public function __construct(
        SalesProductConnectorToUtilEncodingInterface $utilEncodingService,
        SalesProductConnectorQueryContainerInterface $SalesProductConnectorQueryContainer
    ) {
        $this->salesProductConnectorQueryContainer = $SalesProductConnectorQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateItemMetadata(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getItems() as $item) {
            $metadata = $this->findMetadata($item->getIdSalesOrderItem());
            if (!$metadata) {
                continue;
            }

            $metadataTransfer = $this->convertMetadata($metadata);
            $item->setMetadata($metadataTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata|null
     */
    protected function findMetadata($idSalesOrderItem)
    {
        $metadataEntity = $this->salesProductConnectorQueryContainer->queryProductMetadata($idSalesOrderItem)->findOne();

        return $metadataEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata $metadata
     *
     * @return \Generated\Shared\Transfer\ItemMetadataTransfer
     */
    protected function convertMetadata(SpySalesOrderItemMetadata $metadata)
    {
        $metadataTransfer = new ItemMetadataTransfer();
        $metadataTransfer->setFkSalesOrderItem($metadata->getFkSalesOrderItem());
        $metadataTransfer->setSuperAttributes($this->utilEncodingService->decodeJson($metadata->getSuperAttributes(), true));
        $metadataTransfer->setImage($metadata->getImage());

        return $metadataTransfer;
    }
}
