<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Quote;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class QuoteMerger implements QuoteMergerInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    protected $checkoutDataMapper;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface $checkoutDataMapper
     */
    public function __construct(
        CheckoutDataMapperInterface $checkoutDataMapper
    ) {
        $this->checkoutDataMapper = $checkoutDataMapper;
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
            $quoteTransfer->setCustomer($this->prepareGuestCustomerTransfer($restCheckoutRequestAttributesTransfer));

            return $quoteTransfer;
        }

        $quoteTransfer->setCustomer($this->prepareCustomerTransfer($restRequest));

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function prepareGuestCustomerTransfer(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->fromArray(
                $restCheckoutRequestAttributesTransfer->getCart()->getCustomer()->toArray(),
                true
            )
            ->setCustomerReference(null)
            ->setIdCustomer(null);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function prepareCustomerTransfer(RestRequestInterface $restRequest): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setIdCustomer((int)$restRequest->getUser()->getSurrogateIdentifier());
    }
}
