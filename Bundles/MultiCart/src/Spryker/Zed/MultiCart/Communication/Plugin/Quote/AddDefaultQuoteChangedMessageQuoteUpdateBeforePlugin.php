<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * @method \Spryker\Zed\MultiCart\MultiCartConfig getConfig()
 * @method \Spryker\Zed\MultiCart\Business\MultiCartFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiCart\Communication\MultiCartCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface getRepository()
 */
class AddDefaultQuoteChangedMessageQuoteUpdateBeforePlugin extends AbstractPlugin implements QuoteWritePluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if provided `QuoteTransfer.customer` is not the quote owner, or if the quote is default for `QuoteTransfer.customer`.
     * - Adds info message otherwise.
     * - Should be executed before `DeactivateQuotesBeforeQuoteSavePlugin`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $this->getFacade()->addDefaultQuoteChangedMessage($quoteTransfer);

        return $quoteTransfer;
    }
}
