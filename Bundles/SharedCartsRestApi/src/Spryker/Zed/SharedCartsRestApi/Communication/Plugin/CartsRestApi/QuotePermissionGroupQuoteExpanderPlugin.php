<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Communication\Plugin\CartsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()()
 * @method \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiBusinessFactory getFactory()
 */
class QuotePermissionGroupQuoteExpanderPlugin extends AbstractPlugin implements QuoteExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands quote transfer with quote permission group.
     * - Will expand only if QuoteTransfer::$customer is a company user the cart is shared with.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->expandQuoteWithQuotePermissionGroup($quoteTransfer);
    }
}
