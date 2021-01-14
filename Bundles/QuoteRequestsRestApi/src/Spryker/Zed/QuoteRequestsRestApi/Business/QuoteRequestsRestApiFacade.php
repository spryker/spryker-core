<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestsRestApi\Business;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestsRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\QuoteRequestsRestApi\Business\QuoteRequestsRestApiBusinessFactory getFactory()
 */
class QuoteRequestsRestApiFacade extends AbstractFacade implements QuoteRequestsRestApiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(
        QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer
    ): QuoteRequestResponseTransfer {
        return $this->getFactory()
            ->createQuoteRequestCreator()
            ->createQuoteRequest($quoteRequestsRequestTransfer);
    }
}
