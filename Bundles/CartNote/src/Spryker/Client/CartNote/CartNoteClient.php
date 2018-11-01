<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CartNote\CartNoteFactory getFactory()
 */
class CartNoteClient extends AbstractClient implements CartNoteClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $note
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setNoteToQuote(string $note): QuoteResponseTransfer
    {
        return $this->getFactory()->getQuoteStorageStrategy()->setNoteToQuote($note);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $note
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setNoteToQuoteItem(string $note, string $sku, ?string $groupKey = null): QuoteResponseTransfer
    {
        return $this->getFactory()->getQuoteStorageStrategy()->setNoteToQuoteItem($note, $sku, $groupKey);
    }
}
