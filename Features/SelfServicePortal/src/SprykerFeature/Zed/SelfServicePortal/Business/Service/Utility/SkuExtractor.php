<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Utility;

use Generated\Shared\Transfer\CartChangeTransfer;

class SkuExtractor implements SkuExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string>
     */
    public function extractSkusFromCartChange(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku()) {
                $skus[] = $itemTransfer->getSku();
            }
        }

        return $skus;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string>
     */
    public function extractSkusFromItemTransfers(array $itemTransfers): array
    {
        $skus = [];

        foreach ($itemTransfers as $itemTransfer) {
            $skus[] = (string)$itemTransfer->getSku();
        }

        return array_unique($skus);
    }
}
