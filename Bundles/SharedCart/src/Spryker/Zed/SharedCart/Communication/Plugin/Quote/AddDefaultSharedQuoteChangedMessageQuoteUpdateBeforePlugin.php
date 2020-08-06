<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface getRepository()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 */
class AddDefaultSharedQuoteChangedMessageQuoteUpdateBeforePlugin extends AbstractPlugin implements QuoteWritePluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if QuoteTransfer::$customer is a quote owner.
     * - Checks if quote is default for provided QuoteTransfer::$customer::$companyUser.
     * - Adds info message if the quote was not default.
     * - Should be executed before `DeactivateSharedQuotesBeforeQuoteSavePlugin`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $this->getFacade()->addDefaultSharedQuoteChangedMessage($quoteTransfer);

        return $quoteTransfer;
    }
}
