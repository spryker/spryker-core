<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Model;

use Generated\Shared\Transfer\ItemMetadataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface;

class ItemMetadataSaver implements ItemMetadataSaverInterface
{
    use DatabaseTransactionHandlerTrait;

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
     * @param \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface $salesProductConnectorQueryContainer
     */
    public function __construct(
        SalesProductConnectorToUtilEncodingInterface $utilEncodingService,
        SalesProductConnectorQueryContainerInterface $salesProductConnectorQueryContainer
    ) {

        $this->salesProductConnectorQueryContainer = $salesProductConnectorQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveItemsMetadata(QuoteTransfer $quoteTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($quoteTransfer) {
            foreach ($quoteTransfer->getItems() as $item) {
                $this->saveItemMetadata($item);
            }
        });
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
     * @return \Generated\Shared\Transfer\ItemMetadataTransfer
     */
    protected function createMetadataTransfer(ItemTransfer $itemTransfer)
    {
        $image = $this->determineImage($itemTransfer);
        $superAttributes = $this->determineSuperAttributes($itemTransfer);

        $metadataTransfer = new ItemMetadataTransfer();
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

        $matchingAttributes = $this->salesProductConnectorQueryContainer->queryMatchingSuperAttributes($attributeKeys)->find();
        $superAttributes = $this->filterMatchingSuperAttributes($concreteAttributes, iterator_to_array($matchingAttributes));

        return $superAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemMetadataTransfer $productMetadataTransfer
     *
     * @return void
     */
    protected function saveMetadataTransfer(ItemMetadataTransfer $productMetadataTransfer)
    {
        $productMetadataEntity = $this->mapMetadataTransfer($productMetadataTransfer);
        $productMetadataEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemMetadataTransfer $productMetadataTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata
     */
    protected function mapMetadataTransfer(ItemMetadataTransfer $productMetadataTransfer)
    {
        $entity = new SpySalesOrderItemMetadata();
        $entity->setSuperAttributes($this->utilEncodingService->encodeJson($productMetadataTransfer->getSuperAttributes()));
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
