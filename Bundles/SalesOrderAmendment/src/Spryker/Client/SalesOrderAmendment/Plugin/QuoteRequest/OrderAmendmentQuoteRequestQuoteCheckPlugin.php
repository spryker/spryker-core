<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment\Plugin\QuoteRequest;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuoteRequestExtension\Dependency\Plugin\QuoteRequestQuoteCheckPluginInterface;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;

/**
 * @method \Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentFactory getFactory()
 */
class OrderAmendmentQuoteRequestQuoteCheckPlugin extends AbstractPlugin implements QuoteRequestQuoteCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns `false` if quote is in amendment process, `true` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        if (
            $quoteTransfer->getQuoteProcessFlow()
            && $quoteTransfer->getQuoteProcessFlowOrFail()->getName() === SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT
        ) {
            return false;
        }

        return !$quoteTransfer->getAmendmentOrderReference();
    }
}
