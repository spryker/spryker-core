<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 */
class ResetAmendmentOrderReferenceBeforeQuoteSavePlugin extends AbstractPlugin implements QuoteWritePluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `QuoteTransfer.items` is not empty.
     * - Sets `QuoteTransfer.amendmentOrderReference` to null if `QuoteTransfer.amendmentOrderReference` is not null.
     * - Sets `QuoteTransfer.quoteProcessFlow` to null if `QuoteTransfer.quoteProcessFlow.name` is equal to `SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getItems()->count() !== 0) {
            return $quoteTransfer;
        }

        if ($quoteTransfer->getAmendmentOrderReference()) {
            $quoteTransfer->setAmendmentOrderReference(null);
        }

        if (
            $quoteTransfer->getQuoteProcessFlow()
            && $quoteTransfer->getQuoteProcessFlowOrFail()->getName() === SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT
        ) {
            $quoteTransfer->setQuoteProcessFlow(null);
        }

        return $quoteTransfer;
    }
}
