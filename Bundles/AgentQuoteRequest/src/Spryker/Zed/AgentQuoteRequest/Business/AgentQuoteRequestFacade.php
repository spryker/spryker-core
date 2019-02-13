<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Business;

use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequestBusinessFactory getFactory()
 * @method \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestEntityManagerInterface getEntityManager()
 */
class AgentQuoteRequestFacade extends AbstractFacade implements AgentQuoteRequestFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    public function getQuoteRequestOverviewCollection(
        QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
    ): QuoteRequestOverviewCollectionTransfer {
        return $this->getFactory()
            ->createAgentQuoteRequestReader()
            ->getQuoteRequestOverviewCollection($quoteRequestOverviewFilterTransfer);
    }
}
