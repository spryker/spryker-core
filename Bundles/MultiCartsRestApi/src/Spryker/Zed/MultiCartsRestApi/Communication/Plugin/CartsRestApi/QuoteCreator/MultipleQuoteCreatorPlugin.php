<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCartsRestApi\Communication\Plugin\CartsRestApi\QuoteCreator;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MultiCartsRestApi\Business\MultiCartsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiCartsRestApi\MultiCartsRestApiConfig getConfig()
 */
class MultipleQuoteCreatorPlugin extends AbstractPlugin implements QuoteCreatorPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Creates a quote for customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(RestQuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->createQuote($quoteRequestTransfer);
    }
}
