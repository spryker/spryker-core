<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 */
class StartOrderAmendmentDraftCheckoutPostSavePlugin extends AbstractPlugin implements CheckoutPostSaveInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `QuoteTransfer.amendmentOrderReference` is not set.
     * - Triggers OMS event defined in {@link \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig::getStartOrderAmendmentDraftEvent()} to start the order amendment draft.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executeHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $this->getBusinessFactory()->createOrderAmendmentProcessor()->startOrderAmendmentDraft($quoteTransfer);
    }
}
