<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Plugin\Agent;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationSessionFinisherPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Quote\QuoteClientInterface getClient()
 */
class SanitizeCustomerQuoteImpersonationSessionFinisherPlugin extends AbstractPlugin implements ImpersonationSessionFinisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sanitizes customer quote.
     *
     * @api
     *
     * @return void
     */
    public function finish(): void
    {
        $this->getClient()->setQuote(new QuoteTransfer());
    }
}
