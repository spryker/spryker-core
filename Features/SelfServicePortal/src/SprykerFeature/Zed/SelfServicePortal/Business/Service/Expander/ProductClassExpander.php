<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductClassConditionsTransfer;
use Generated\Shared\Transfer\ProductClassCriteriaTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Indexer\ProductClassIndexerInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Utility\SkuExtractorInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductClassExpander implements ProductClassExpanderInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
        protected ProductClassIndexerInterface $productClassIndexer,
        protected SkuExtractorInterface $skuExtractor
    ) {
    }

    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $skus = $this->skuExtractor->extractSkusFromCartChange($cartChangeTransfer);

        if (!$skus) {
            return $cartChangeTransfer;
        }

        $productClassConditionsTransfer = (new ProductClassConditionsTransfer())->setSkus($skus);
        $productClassCriteriaTransfer = (new ProductClassCriteriaTransfer())->setProductClassConditions($productClassConditionsTransfer);
        $productClassCollectionTransfer = $this->selfServicePortalRepository->getProductClassCollection($productClassCriteriaTransfer);

        $indexedProductClasses = $this->productClassIndexer->getProductClassesIndexedBySku($productClassCollectionTransfer->getProductClasses()->getArrayCopy());

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $sku = $itemTransfer->getSku();

            if (isset($indexedProductClasses[$sku])) {
                $itemTransfer->setProductClasses(new ArrayObject($indexedProductClasses[$sku]));
            }
        }

        return $cartChangeTransfer;
    }

    public function expandProductPageDataTransferWithProductClasses(
        ProductPageLoadTransfer $productPageLoadTransfer
    ): ProductPageLoadTransfer {
        $productAbstractIds = $this->extractProductAbstractIds($productPageLoadTransfer);

        if (!$productAbstractIds) {
            return $productPageLoadTransfer;
        }

        $indexedProductClasses = $this->getProductClassesIndexedByProductAbstractIds($productAbstractIds);

        if (!$indexedProductClasses) {
            return $productPageLoadTransfer;
        }

        foreach ($productPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            $idProductAbstract = $payloadTransfer->getIdProductAbstract();

            if (isset($indexedProductClasses[$idProductAbstract])) {
                $payloadTransfer->setProductClassNames($this->getProductClassNames($indexedProductClasses[$idProductAbstract]));
            }
        }

        return $productPageLoadTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return array<int|string|null>
     */
    protected function getProductClassNames(array $productClassTransfers): array
    {
        $productClassNames = [];

        foreach ($productClassTransfers as $productClassTransfer) {
            $productClassNames[] = $productClassTransfer->getNameOrFail();
        }

        return $productClassNames;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return array<int>
     */
    protected function extractProductAbstractIds(ProductPageLoadTransfer $productPageLoadTransfer): array
    {
        $productAbstractIds = [];

        foreach ($productPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            if (!$payloadTransfer->getIdProductAbstract()) {
                continue;
            }

            $productAbstractIds[] = $payloadTransfer->getIdProductAbstract();
        }

        return $productAbstractIds;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    protected function getProductClassesIndexedByProductAbstractIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        return $this->buildIndexedProductClassesByProductAbstractId($productAbstractIds);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    protected function buildIndexedProductClassesByProductAbstractId(array $productAbstractIds): array
    {
        $productClassConditionsTransfer = (new ProductClassConditionsTransfer())
            ->setProductAbstractIds($productAbstractIds);

        $productClassCriteriaTransfer = (new ProductClassCriteriaTransfer())
            ->setProductClassConditions($productClassConditionsTransfer);

        $productClassCollectionTransfer = $this->selfServicePortalRepository
            ->getProductClassCollection($productClassCriteriaTransfer);

        return $this->productClassIndexer->getProductClassesIndexedByProductAbstractId(
            $productClassCollectionTransfer->getProductClasses()->getArrayCopy(),
        );
    }
}
