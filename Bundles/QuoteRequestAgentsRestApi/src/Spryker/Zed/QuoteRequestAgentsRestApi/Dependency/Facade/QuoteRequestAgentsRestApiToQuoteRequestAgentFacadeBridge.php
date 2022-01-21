<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

class QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeBridge implements QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequestAgent\Business\QuoteRequestAgentFacadeInterface
     */
    protected $quoteRequestAgentFacade;

    /**
     * @param \Spryker\Zed\QuoteRequestAgent\Business\QuoteRequestAgentFacadeInterface $quoteRequestAgentFacade
     */
    public function __construct($quoteRequestAgentFacade)
    {
        $this->quoteRequestAgentFacade = $quoteRequestAgentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestAgentFacade->updateQuoteRequest($quoteRequestTransfer);
    }
}
