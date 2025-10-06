<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorConfig getConfig()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacadeInterface getFacade()
 */
class EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePlugin extends AbstractPlugin implements CheckoutPreSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `QuoteTransfer.originalOrder` is set.
     * - Does nothing if `QuoteTransfer.customer.companyUserTransfer` does not exist or does not have permission
     *  to edit business unit orders to which `QuoteTransfer.amendmentOrderReference` belongs to.
     * - Otherwise finds and sets `QuoteTransfer.originalOrder` by `QuoteTransfer.amendmentOrderReference`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preSave(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getBusinessFactory()
            ->createEditCompanyBusinessUnitOrderQuoteExpander()
            ->expandQuoteWithOriginalOrder($quoteTransfer);
    }
}
