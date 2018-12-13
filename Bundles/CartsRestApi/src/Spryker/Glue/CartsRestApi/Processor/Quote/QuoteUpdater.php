<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Quote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class QuoteUpdater implements QuoteUpdaterInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface $persistentCartClient
     */
    public function __construct(CartsRestApiToPersistentCartClientInterface $persistentCartClient)
    {
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(RestRequestInterface $restRequest, QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteUpdateRequestTransfer = (new QuoteUpdateRequestTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setCustomer($quoteTransfer->getCustomer());
        $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
            ->fromArray($quoteTransfer->modifiedToArray(), true);
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }
}
