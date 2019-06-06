<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuotePersistPluginInterface;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 * @method \Spryker\Client\PersistentCart\PersistentCartClientInterface getClient()
 */
class PersistentCartQuotePersistPlugin extends AbstractPlugin implements QuotePersistPluginInterface
{
    /**
     * {@inheritdoc}
     * - Retrieves a quote from Persistence using the provided customer and store information.
     * - Replaces the retrieved quote with the provided quote and stores it in Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function persist(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getClient()->replaceQuoteByCustomerAndStore($quoteTransfer);
    }
}
