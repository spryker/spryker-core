<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuoteExtension\Dependency\Plugin\QuoteTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\Agent\AgentClient getClient()
 * @method \Spryker\Client\Agent\AgentFactory getFactory()
 */
class AgentQuoteTransferExpanderPlugin extends AbstractPlugin implements QuoteTransferExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds agent's user email to the Quote if an Agent is logged-in and isSalesOrderAgentEnabled feature flag is enabled.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuote(QuoteTransfer $quoteTransfer)
    {
        if (!$this->getFactory()->getConfig()->isSalesOrderAgentEnabled()) {
            return $quoteTransfer;
        }

        if (!$this->getClient()->isLoggedIn()) {
            return $quoteTransfer;
        }

        $userTransfer = $this->getClient()->getAgent();
        $quoteTransfer->setAgentEmail($userTransfer->getUsername());

        return $quoteTransfer;
    }
}
