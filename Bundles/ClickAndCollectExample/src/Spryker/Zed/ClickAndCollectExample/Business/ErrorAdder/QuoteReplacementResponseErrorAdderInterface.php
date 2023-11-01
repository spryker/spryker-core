<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;

interface QuoteReplacementResponseErrorAdderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    public function addError(QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer, ItemTransfer $itemTransfer): QuoteReplacementResponseTransfer;
}
