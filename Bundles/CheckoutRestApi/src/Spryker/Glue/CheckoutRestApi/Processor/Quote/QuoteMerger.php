<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Quote;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class QuoteMerger implements QuoteMergerInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    protected $checkoutDataMapper;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface $checkoutDataMapper
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientInterface $customerClient
     */
    public function __construct(
        CheckoutDataMapperInterface $checkoutDataMapper,
        CheckoutRestApiToCustomerClientInterface $customerClient
    ) {
        $this->checkoutDataMapper = $checkoutDataMapper;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateQuoteWithDataFromRequest(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestRequestInterface $restRequest
    ): QuoteTransfer {
        $quoteTransfer = $this->checkoutDataMapper->mapRestCheckoutRequestAttributesTransferToQuoteTransfer(
            $quoteTransfer,
            $restCheckoutRequestAttributesTransfer
        );
        $quoteTransfer = $this->updateQuoteWithCustomerFromRequest($quoteTransfer, $restCheckoutRequestAttributesTransfer, $restRequest);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteWithCustomerFromRequest(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestRequestInterface $restRequest
    ): QuoteTransfer {
        if (!$restRequest->getUser()->getSurrogateIdentifier()) {
            $quoteTransfer->setCustomer(
                (new CustomerTransfer())
                    ->fromArray(
                        $restCheckoutRequestAttributesTransfer->getCart()->getCustomer()->toArray(),
                        true
                    )
            );

            return $quoteTransfer;
        }

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);
        $quoteTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());

        return $quoteTransfer;
    }
}
