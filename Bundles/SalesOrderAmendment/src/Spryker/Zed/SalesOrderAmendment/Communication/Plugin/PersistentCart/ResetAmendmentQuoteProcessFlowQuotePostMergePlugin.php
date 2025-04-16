<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\PersistentCart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuotePostMergePluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentBusinessFactory getBusinessFactory()()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 */
class ResetAmendmentQuoteProcessFlowQuotePostMergePlugin extends AbstractPlugin implements QuotePostMergePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `PersistentQuoteTransfer.idQuote` to be set.
     * - Requires `CurrentQuoteTransfer.idQuote` to be set.
     * - If `PersistentQuoteTransfer.idQuote` is not equal to `CurrentQuoteTransfer.idQuote`, it will reset the `quoteProcessFlow` if it is set to `order-amendment`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $persistentQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $currentQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function postMerge(QuoteTransfer $persistentQuoteTransfer, QuoteTransfer $currentQuoteTransfer): QuoteTransfer
    {
        if ($persistentQuoteTransfer->getIdQuoteOrFail() === $currentQuoteTransfer->getIdQuoteOrFail()) {
            return $persistentQuoteTransfer;
        }

        if (
            $persistentQuoteTransfer->getQuoteProcessFlow()?->getName() === SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT
        ) {
            $persistentQuoteTransfer->setQuoteProcessFlow(null);
        }

        return $persistentQuoteTransfer;
    }
}
