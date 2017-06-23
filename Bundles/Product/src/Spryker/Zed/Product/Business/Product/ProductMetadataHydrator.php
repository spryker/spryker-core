<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMetadataTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductMetadataHydrator implements ProductMetadataHydratorInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductMetadata(OrderTransfer $orderTransfer)
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
     * @return \Generated\Shared\Transfer\ProductMetadataTransfer
     */
    protected function convertMetadata(SpySalesOrderItemMetadata $metadata)
    {
        $metadataTransfer = new ProductMetadataTransfer();
        $metadataTransfer->setFkSalesOrderItem($metadata->getFkSalesOrderItem());
        $metadataTransfer->setSuperAttributes(json_decode($metadata->getSuperAttributes(), true));
        $metadataTransfer->setImage($metadata->getImage());

        return $metadataTransfer;
    }

    /**
     * @param array $concreteAttributes
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKey[] $matchingAttributes
     *
     * @return array
     */
    protected function filterMatchingSuperAttributes(array $concreteAttributes, array $matchingAttributes)
    {
        if (count($matchingAttributes) === 0) {
            return [];
        }

        $result = [];

        foreach ($concreteAttributes as $key => $value) {
            foreach ($matchingAttributes as $matchingAttribute) {
                if ($matchingAttribute->getKey() === $key) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

}
