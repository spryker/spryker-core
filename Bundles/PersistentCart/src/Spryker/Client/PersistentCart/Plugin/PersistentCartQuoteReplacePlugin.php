<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteReplacePluginInterface;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 */
class PersistentCartQuoteReplacePlugin extends AbstractPlugin implements QuoteReplacePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function replace(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createZedPersistentCartStub()->replaceQuote($quoteTransfer);
    }
}
