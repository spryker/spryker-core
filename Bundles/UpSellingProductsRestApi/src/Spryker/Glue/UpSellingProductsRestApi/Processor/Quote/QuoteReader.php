<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi\Processor\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToCartsRestApiClientInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToCartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @param \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToCartsRestApiClientInterface $cartsRestApiClient
     */
    public function __construct(UpSellingProductsRestApiToCartsRestApiClientInterface $cartsRestApiClient)
    {
        $this->cartsRestApiClient = $cartsRestApiClient;
    }

    /**
     * @param string $uuid
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByUuid(string $uuid, RestRequestInterface $restRequest): ?QuoteTransfer
    {
        $quoteTransfer = (new QuoteTransfer())->setUuid($uuid);
        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return null;
        }

        $customerReference = $restRequest->getUser()->getNaturalIdentifier();
        if ($quoteResponseTransfer->getQuoteTransfer()->getCustomerReference() !== $customerReference) {
            return null;
        }

        return $quoteResponseTransfer->getQuoteTransfer();
    }
}
