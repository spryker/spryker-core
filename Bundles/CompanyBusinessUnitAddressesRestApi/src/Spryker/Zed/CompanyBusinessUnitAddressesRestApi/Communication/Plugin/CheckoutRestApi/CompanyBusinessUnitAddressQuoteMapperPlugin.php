<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiConfig getConfig()
 */
class CompanyBusinessUnitAddressQuoteMapperPlugin extends AbstractPlugin implements QuoteMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Applicable to rest request addresses which have companyBusinessUnitAddressId property.
     * - Maps rest request billing company business unit address information to quote.
     * - Maps rest request shipping company business unit address information to quote level (BC) and item level shipping addresses.
     * - Must be setup after `AddressQuoteMapperPlugin`.
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
        return $this->getFacade()
            ->mapCompanyBusinessUnitAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }
}
