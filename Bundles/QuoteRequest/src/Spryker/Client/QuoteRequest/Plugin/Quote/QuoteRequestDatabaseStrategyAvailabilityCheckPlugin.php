<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuoteExtension\Dependency\Plugin\DatabaseStrategyAvailabilityCheckPluginInterface;

class QuoteRequestDatabaseStrategyAvailabilityCheckPlugin extends AbstractPlugin implements DatabaseStrategyAvailabilityCheckPluginInterface
{
    /**
     * {@inheritdoc}
     * - Disallow database strategy when quoteRequestReference provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isAllowed(QuoteTransfer $quoteTransfer): bool
    {
        return !(bool)$quoteTransfer->getQuoteRequestReference();
    }
}
