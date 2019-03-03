<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteReplacePluginInterface;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 * @method \Spryker\Client\PersistentCart\PersistentCartClientInterface getClient()
 */
class PersistentCartQuoteReplacePlugin extends AbstractPlugin implements QuoteReplacePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replace(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getClient()->replaceQuote($quoteTransfer);
    }
}
