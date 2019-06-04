<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi\Processor\Quote;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
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
        $customerTransfer = $this->createCustomerTransfer($restRequest->getRestUser());
        $quoteTransfer = (new QuoteTransfer())
            ->setUuid($uuid)
            ->setCustomer($customerTransfer);
        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $quoteResponseTransfer->getQuoteTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(
        RestUserTransfer $restUserTransfer
    ): CustomerTransfer {
        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restUserTransfer->getNaturalIdentifier())
            ->setIdCustomer($restUserTransfer->getSurrogateIdentifier());

        return $customerTransfer;
    }
}
