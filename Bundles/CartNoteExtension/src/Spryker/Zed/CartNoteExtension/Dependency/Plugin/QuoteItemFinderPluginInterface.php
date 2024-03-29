<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNoteExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteItemFinderPluginInterface
{
    /**
     * Specification:
     * - Find items in quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function findItem(QuoteTransfer $quoteTransfer, $sku, $groupKey): array;
}
