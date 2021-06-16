<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Plugin\QuoteRequest;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuoteRequestExtension\Dependency\Plugin\QuoteRequestQuoteCheckPluginInterface;

/**
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationClientInterface getClient()
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationFactory getFactory()
 */
class ProductConfigurationQuoteRequestQuoteCheckPlugin extends AbstractPlugin implements QuoteRequestQuoteCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns false if any item with product configuration is not fully configured, true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getClient()->isQuoteProductConfigurationValid($quoteTransfer);
    }
}
