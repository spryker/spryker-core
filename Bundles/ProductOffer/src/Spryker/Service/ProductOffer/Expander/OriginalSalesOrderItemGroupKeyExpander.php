<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductOffer\Expander;

use Generated\Shared\Transfer\ItemTransfer;

class OriginalSalesOrderItemGroupKeyExpander implements OriginalSalesOrderItemGroupKeyExpanderInterface
{
    /**
     * @var string
     */
    protected const GROUP_KEY_DELIMITER = '_';

    /**
     * @param string $groupKey
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function expandGroupKey(string $groupKey, ItemTransfer $itemTransfer): string
    {
        if ($itemTransfer->getProductOfferReference()) {
            $groupKey = $groupKey . static::GROUP_KEY_DELIMITER . $itemTransfer->getProductOfferReference();
        }

        return $groupKey;
    }
}
