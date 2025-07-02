<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductClassExpander implements ProductClassExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(protected SelfServicePortalRepositoryInterface $selfServicePortalRepository)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $skus = $this->extractSkus($cartChangeTransfer);
        $indexedProductClasses = $this->selfServicePortalRepository->getProductClassesForConcreteProductsBySkusIndexedBySku($skus);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $sku = $itemTransfer->getSku();

            if (isset($indexedProductClasses[$sku])) {
                $itemTransfer->setProductClasses(new ArrayObject($indexedProductClasses[$sku]));
            }
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransferWithProductClasses(
        ProductPageLoadTransfer $productPageLoadTransfer
    ): ProductPageLoadTransfer {
        $productAbstractIds = $this->extractProductAbstractIds($productPageLoadTransfer);
        $indexedProductClasses = $this->selfServicePortalRepository->getProductClassesByProductAbstractIds($productAbstractIds);

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
     * @return array<string>
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
            $productAbstractIds[] = $payloadTransfer->getIdProductAbstract();
        }

        return $productAbstractIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string>
     */
    protected function extractSkus(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku()) {
                $skus[] = $itemTransfer->getSku();
            }
        }

        return $skus;
    }
}
