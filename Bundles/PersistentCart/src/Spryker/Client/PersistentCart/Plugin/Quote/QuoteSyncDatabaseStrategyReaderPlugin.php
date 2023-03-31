<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuoteExtension\Dependency\Plugin\DatabaseStrategyReaderPluginInterface;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 * @method \Spryker\Client\PersistentCart\PersistentCartClientInterface getClient()
 */
class QuoteSyncDatabaseStrategyReaderPlugin extends AbstractPlugin implements DatabaseStrategyReaderPluginInterface
{
 /**
  * {@inheritDoc}
  * - Makes Zed request in case of persistent strategy and `QuoteTransfer.id` is empty.
  * - Retrieves a quote from Persistence using the provided customer in case of persistent strategy and `QuoteTransfer.id` is empty.
  * - Sets retrieved quote from Persistence in session storage in case of persistent strategy and `QuoteTransfer.id` is empty.
  * - Executes {@link \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface} plugins in case of persistent strategy and `QuoteTransfer.id` is empty.
  *
  * @api
  *
  * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
  *
  * @return \Generated\Shared\Transfer\QuoteTransfer
  */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getClient()->syncQuote($quoteTransfer);
    }
}
