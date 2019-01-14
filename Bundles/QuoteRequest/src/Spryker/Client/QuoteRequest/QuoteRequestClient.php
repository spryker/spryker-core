<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface;

/**
 * @method \Spryker\Client\QuoteRequest\QuoteRequestFactory getFactory()
 */
class QuoteRequestClient extends AbstractClient implements QuoteRequestClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequestFromQuote(QuoteTransfer $quoteTransfer): QuoteRequestTransfer
    {
        $quoteRequestTransfer = $this->getZedStub()->createQuoteRequestFromQuote($quoteTransfer);

        return $quoteRequestTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getCustomerQuoteRequestCollection(
        CustomerTransfer $customerTransfer
    ): QuoteRequestCollectionTransfer {
        $quoteRequestCollectionTransfer = $this->getZedStub()->getCustomerQuoteRequestCollection($customerTransfer);

        return $quoteRequestCollectionTransfer;
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface
     */
    protected function getZedStub(): QuoteRequestStubInterface
    {
        return $this->getFactory()->createQuoteRequestStub();
    }
}
