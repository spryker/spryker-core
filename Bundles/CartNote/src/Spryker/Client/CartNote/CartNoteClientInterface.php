<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote;

use Generated\Shared\Transfer\QuoteResponseTransfer;

/**
 * @method \Spryker\Client\CartNote\CartNoteFactory getFactory()
 */
interface CartNoteClientInterface
{
    /**
     * Specification:
     * - Set Cart note to quote.
     *
     * @api
     *
     * @param string $note
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setNoteToQuote(string $note): QuoteResponseTransfer;

    /**
     * Specification::
     * - Set Cart note to quote item.
     *
     * @api
     *
     * @param string $note
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setNoteToQuoteItem(string $note, string $sku, ?string $groupKey = null): QuoteResponseTransfer;
}
