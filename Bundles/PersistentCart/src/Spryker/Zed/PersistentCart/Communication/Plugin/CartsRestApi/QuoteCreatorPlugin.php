<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Plugin\CartsRestApi;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface getFacade()
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 * @method \Spryker\Zed\PersistentCart\Communication\PersistentCartCommunicationFactory getFactory()
 */
class QuoteCreatorPlugin extends AbstractPlugin implements QuoteCreatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates quote for customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->createQuote($quoteTransfer);
    }
}
