<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 */
class OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin extends AbstractPlugin implements CartPreReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `CartReorderRequestTransfer.isAmendment` flag is not set.
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Uses {@link \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig::getOrderAmendmentQuoteProcessFlowName()} to get the name of the quote process flow.
     * - Expands `CartReorderTransfer.quote.quoteProcessFlow` with the quote process flow name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function preReorder(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        if (!$cartReorderRequestTransfer->getIsAmendment()) {
            return $cartReorderTransfer;
        }

        $cartReorderTransfer->getQuoteOrFail()->setQuoteProcessFlow(
            (new QuoteProcessFlowTransfer())->setName($this->getConfig()->getOrderAmendmentQuoteProcessFlowName()),
        );

        return $cartReorderTransfer;
    }
}
