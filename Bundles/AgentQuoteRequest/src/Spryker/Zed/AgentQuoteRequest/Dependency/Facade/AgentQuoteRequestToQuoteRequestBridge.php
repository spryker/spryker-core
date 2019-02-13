<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Dependency\Facade;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;

class AgentQuoteRequestToQuoteRequestBridge implements AgentQuoteRequestToQuoteRequestInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Business\QuoteRequestFacadeInterface
     */
    protected $quoteRequestFacade;

    /**
     * @param \Spryker\Zed\QuoteRequest\Business\QuoteRequestFacadeInterface $quoteRequestFacade
     */
    public function __construct($quoteRequestFacade)
    {
        $this->quoteRequestFacade = $quoteRequestFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        return $this->quoteRequestFacade->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);
    }
}
