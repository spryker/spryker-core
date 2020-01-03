<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Plugin\QuoteRequest;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuoteRequestExtension\Dependency\Plugin\QuoteRequestQuoteCheckPluginInterface;

/**
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalClientInterface getClient()
 */
class QuoteApprovalQuoteRequestQuoteCheckPlugin extends AbstractPlugin implements QuoteRequestQuoteCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns false if quote does't have status `waiting`, true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        return !$this->getClient()->isQuoteWaitingForApproval($quoteTransfer);
    }
}
