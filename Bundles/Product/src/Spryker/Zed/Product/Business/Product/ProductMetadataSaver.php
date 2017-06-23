<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMetadataTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductMetadataSaver implements ProductMetadataSaverInterface
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveProductMetadata(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $item) {
            $this->saveItemMetadata($item);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function saveItemMetadata(ItemTransfer $itemTransfer)
    {
        $metadataTransfer = $this->createMetadataTransfer($itemTransfer);
        $this->saveMetadataTransfer($metadataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMetadataTransfer
     */
    protected function createMetadataTransfer(ItemTransfer $itemTransfer)
    {
        $image = $this->determineImage($itemTransfer);
        $superAttributes = $this->determineSuperAttributes($itemTransfer);

        $metadataTransfer = new ProductMetadataTransfer();
        $metadataTransfer->setImage($image);
        $metadataTransfer->setSuperAttributes($superAttributes);
        $metadataTransfer->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem());

        return $metadataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    protected function determineImage(ItemTransfer $itemTransfer)
    {
        $images = $itemTransfer->getImages();
        if (!$images) {
            return null;
        }

        if (count($images) === 0) {
            return null;
        }

        return $images[0]->getExternalUrlSmall();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function determineSuperAttributes(ItemTransfer $itemTransfer)
    {
        $concreteAttributes = $itemTransfer->getConcreteAttributes();
        $attributeKeys = array_keys($concreteAttributes);

        $matchingAttributes = $this->productQueryContainer->queryMatchingSuperAttributes($attributeKeys)->find();
        $superAttributes = $this->filterMatchingSuperAttributes($concreteAttributes, iterator_to_array($matchingAttributes));

        return $superAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMetadataTransfer $productMetadataTransfer
     *
     * @return void
     */
    protected function saveMetadataTransfer(ProductMetadataTransfer $productMetadataTransfer)
    {
        $productMetadataEntity = $this->mapMetadataTransfer($productMetadataTransfer);
        $productMetadataEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMetadataTransfer $productMetadataTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata
     */
    protected function mapMetadataTransfer(ProductMetadataTransfer $productMetadataTransfer)
    {
        $entity = new SpySalesOrderItemMetadata();
        $entity->setSuperAttributes(json_encode($productMetadataTransfer->getSuperAttributes()));
        $entity->setImage($productMetadataTransfer->getImage());
        $entity->setFkSalesOrderItem($productMetadataTransfer->getFkSalesOrderItem());

        return $entity;
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
