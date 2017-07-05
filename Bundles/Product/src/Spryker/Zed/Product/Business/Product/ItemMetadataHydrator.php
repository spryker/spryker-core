<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ItemMetadataTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ItemMetadataHydrator implements ItemMetadataHydratorInterface
{

    /**
     * @var \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(
        ProductToUtilEncodingInterface $utilEncodingService,
        ProductQueryContainerInterface $productQueryContainer
    ) {
        $this->productQueryContainer = $productQueryContainer;
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
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata
     */
    protected function findMetadata($idSalesOrderItem)
    {
        $metadataEntity = $this->productQueryContainer->queryProductMetadata($idSalesOrderItem)->findOne();

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
