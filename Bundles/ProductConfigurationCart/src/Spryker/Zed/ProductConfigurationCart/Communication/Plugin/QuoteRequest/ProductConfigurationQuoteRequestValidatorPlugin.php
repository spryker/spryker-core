<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Communication\Plugin\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestValidatorPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig getConfig()
 * @method \Spryker\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacadeInterface getFacade()
 */
class ProductConfigurationQuoteRequestValidatorPlugin extends AbstractPlugin implements QuoteRequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates configurable products in a quote request.
     * - Expects `QuoteRequestTransfer.latestVersion` and `QuoteRequestTransfer.latestVersion.quote` to be set.
     * - Returns "isSuccessful=true" if all items with a product configuration are fully configured.
     * - Returns "isSuccessful=false" and adds an error message if any item with product configuration is not fully configured.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function validate(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getFacade()->validateQuoteRequestProductConfiguration($quoteRequestTransfer);
    }
}
