<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Quote;

use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartsRestApiClientInterface;

class QuoteProcessor implements QuoteProcessorInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface $cartClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartsRestApiClientInterface $cartsRestApiClient
     */
    public function __construct(
        CheckoutRestApiToCartClientInterface $cartClient,
        CheckoutRestApiToCartsRestApiClientInterface $cartsRestApiClient
    ) {
        $this->cartClient = $cartClient;
        $this->cartsRestApiClient = $cartsRestApiClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findCustomerQuote(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer
    {
        $idCart = $restCheckoutRequestAttributesTransfer->getCart()->getId();
        $quoteCollectionTransfer = $this->cartsRestApiClient->getQuoteCollectionByCriteria(new QuoteCriteriaFilterTransfer());
        foreach ($quoteCollectionTransfer->getQuotes() as $customerQuote) {
            if ($customerQuote->getUuid() === $idCart) {
                return $customerQuote;
            }
        }

        return null;
    }

    /**
     * @return void
     */
    public function clearQuote(): void
    {
        $this->cartClient->clearQuote();
    }
}
