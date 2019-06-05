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
        $quoteTransfer = $this->createQuoteTransfer($uuid, $restRequest->getRestUser());

        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $quoteResponseTransfer->getQuoteTransfer();
    }

    /**
     * @param string $uuid
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(
        string $uuid,
        RestUserTransfer $restUserTransfer
    ): QuoteTransfer {
        $customerReference = $restUserTransfer->getNaturalIdentifier();

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($customerReference)
            ->setIdCustomer($restUserTransfer->getSurrogateIdentifier());

        return (new QuoteTransfer())
            ->setUuid($uuid)
            ->setCustomerReference($customerReference)
            ->setCustomer($customerTransfer);
    }
}
