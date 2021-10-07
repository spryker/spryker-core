<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Resolver;

use ArrayObject;

class ProductImageSetResolver implements ProductImageSetResolverInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     * @param string $localeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function resolveProductImageSetsForLocale(ArrayObject $productImageSetTransfers, string $localeName): ArrayObject
    {
        $resolvedProductImageSetTransfers = $this->extractLocalizedProductImageSets($productImageSetTransfers, $localeName);
        $resolvedProductImageSetTransfers += $this->extractDefaultProductImageSets($productImageSetTransfers);

        return new ArrayObject($resolvedProductImageSetTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    protected function extractLocalizedProductImageSets(ArrayObject $productImageSetTransfers, string $localeName): array
    {
        $localizedProductImageSetTransfers = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            if (!$productImageSetTransfer->getLocale() || $productImageSetTransfer->getLocale()->getLocaleName() !== $localeName) {
                continue;
            }

            $localizedProductImageSetTransfers[$productImageSetTransfer->getName()] = $productImageSetTransfer;
        }

        return $localizedProductImageSetTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    protected function extractDefaultProductImageSets(ArrayObject $productImageSetTransfers): array
    {
        $defaultProductImageSetTransfers = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            if (!$productImageSetTransfer->getLocale()) {
                $defaultProductImageSetTransfers[$productImageSetTransfer->getName()] = $productImageSetTransfer;
            }
        }

        return $defaultProductImageSetTransfers;
    }
}
