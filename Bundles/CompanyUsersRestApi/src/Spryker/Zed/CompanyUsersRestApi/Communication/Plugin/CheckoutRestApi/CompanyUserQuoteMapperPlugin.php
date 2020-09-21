<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUsersRestApi\Business\CompanyUsersRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUsersRestApi\CompanyUsersRestApiConfig getConfig()
 * @method \Spryker\Zed\CompanyUsersRestApi\Communication\CompanyUsersRestApiCommunicationFactory getFactory()
 */
class CompanyUserQuoteMapperPlugin extends AbstractPlugin implements QuoteMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps the company account information from `RestCheckoutRequestAttributesTransfer` to `QuoteTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function map(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        return $this->getFacade()->mapCompanyUserToQuoteTransfer($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }
}
