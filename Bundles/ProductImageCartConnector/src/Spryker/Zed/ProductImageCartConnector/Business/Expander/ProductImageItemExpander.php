<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetConditionsTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Spryker\Shared\ProductImageCartConnector\ProductImageCartConnectorConfig;
use Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToLocaleFacadeInterface;
use Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageFacadeInterface;

class ProductImageItemExpander implements ProductImageItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageFacadeInterface
     */
    protected ProductImageCartConnectorToProductImageFacadeInterface $productImageFacade;

    /**
     * @var \Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToLocaleFacadeInterface
     */
    protected ProductImageCartConnectorToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageFacadeInterface $productImageFacade
     * @param \Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductImageCartConnectorToProductImageFacadeInterface $productImageFacade,
        ProductImageCartConnectorToLocaleFacadeInterface $localeFacade
    ) {
        $this->productImageFacade = $productImageFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $productConcreteIds = $this->extractProductConcreteIds($cartChangeTransfer->getItems());

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions(
                (new ProductImageSetConditionsTransfer())
                    ->setProductConcreteIds($productConcreteIds)
                    ->setAddFallbackLocale(true)
                    ->addName(ProductImageCartConnectorConfig::DEFAULT_IMAGE_SET_NAME)
                    ->addIdLocale($this->localeFacade->getCurrentLocale()->getIdLocaleOrFail()),
            );

        $productImageSetCollectionTransfer = $this->productImageFacade->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->expandItemTransferWithConcreteProductImages($itemTransfer, $productImageSetCollectionTransfer);
        }

        if ($productImageSetCollectionTransfer->getProductImageSets()->count() < count($productConcreteIds)) {
            $this->expandCardChangeItemsWithAbstractProductImages($cartChangeTransfer->getItems());
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    protected function expandCardChangeItemsWithAbstractProductImages(ArrayObject $itemTransfers): void
    {
        $productAbstractIds = $this->extractProductAbstractIds($itemTransfers);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions(
                (new ProductImageSetConditionsTransfer())
                    ->setProductAbstractIds($productAbstractIds)
                    ->setAddFallbackLocale(true)
                    ->addName(ProductImageCartConnectorConfig::DEFAULT_IMAGE_SET_NAME)
                    ->addIdLocale($this->localeFacade->getCurrentLocale()->getIdLocaleOrFail()),
            );
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getImages()->count()) {
                continue;
            }

            $this->expandItemTransferWithAbstractProductImages($itemTransfer, $productImageSetCollectionTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     *
     * @return void
     */
    protected function expandItemTransferWithConcreteProductImages(
        ItemTransfer $itemTransfer,
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
    ): void {
        foreach ($productImageSetCollectionTransfer->getProductImageSets() as $productImageSetTransfer) {
            if ($itemTransfer->getId() === $productImageSetTransfer->getIdProduct()) {
                $itemTransfer->setImages($productImageSetTransfer->getProductImages());
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     *
     * @return void
     */
    protected function expandItemTransferWithAbstractProductImages(
        ItemTransfer $itemTransfer,
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
    ): void {
        foreach ($productImageSetCollectionTransfer->getProductImageSets() as $productImageSetTransfer) {
            if ($itemTransfer->getIdProductAbstract() === $productImageSetTransfer->getIdProductAbstract()) {
                $itemTransfer->setImages($productImageSetTransfer->getProductImages());
            }
        }
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<int>
     */
    protected function extractProductConcreteIds(ArrayObject $itemTransfers): array
    {
        $productConcreteIds = [];
        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteIds[] = $itemTransfer->getIdOrFail();
        }

        return $productConcreteIds;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<int>
     */
    protected function extractProductAbstractIds(ArrayObject $itemTransfers): array
    {
        $productConcreteIds = [];
        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteIds[] = $itemTransfer->getIdProductAbstractOrFail();
        }

        return $productConcreteIds;
    }
}
