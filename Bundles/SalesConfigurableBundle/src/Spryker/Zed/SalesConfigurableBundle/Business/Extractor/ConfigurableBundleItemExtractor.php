<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Extractor;

use ArrayObject;

class ConfigurableBundleItemExtractor implements ConfigurableBundleItemExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function extractItemsWithConfigurableBundle(ArrayObject $itemTransfers): array
    {
        $itemsWithConfigurableBundle = [];
        foreach ($itemTransfers as $index => $itemTransfer) {
            if ($itemTransfer->getSalesOrderConfiguredBundle() && $itemTransfer->getSalesOrderConfiguredBundleItem()) {
                $itemsWithConfigurableBundle[$index] = $itemTransfer;
            }
        }

        return $itemsWithConfigurableBundle;
    }
}
