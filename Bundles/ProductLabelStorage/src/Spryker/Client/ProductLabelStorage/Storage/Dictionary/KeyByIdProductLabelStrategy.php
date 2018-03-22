<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage\Dictionary;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;

class KeyByIdProductLabelStrategy implements KeyStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer
     *
     * @return int
     */
    public function getDictionaryKey(ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer)
    {
        return $productLabelDictionaryItemTransfer->getIdProductLabel();
    }
}
