<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Business;

use Generated\Shared\Transfer\QuoteCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteItemCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CartNote\Business\CartNoteBusinessFactory getFactory()
 */
class CartNoteFacade extends AbstractFacade implements CartNoteFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderCartNote(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFactory()
            ->createCartNoteSaver()
            ->saveCartNoteToOrder($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * Specification:
     *  - Saves cart note to order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCartNoteRequestTransfer $quoteCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteNote(QuoteCartNoteRequestTransfer $quoteCartNoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteCartNoteSetter()
            ->setQuoteNote($quoteCartNoteRequestTransfer);
    }

    /**
     * Specification:
     *  - Saves cart note to order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteItemCartNoteRequestTransfer $quoteItemCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteItemNote(QuoteItemCartNoteRequestTransfer $quoteItemCartNoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteCartNoteSetter()
            ->setQuoteItemNote($quoteItemCartNoteRequestTransfer);
    }
}
