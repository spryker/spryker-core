<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestsRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class QuoteRequestsRequestMapper implements QuoteRequestsRequestMapperInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteRequestsRequestTransfer
     */
    public function mapRestRequestToQuoteRequestsRequestTransfer(RestRequestInterface $restRequest): QuoteRequestsRequestTransfer
    {
        /** @var \Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer $quoteRequestsRequestAttributesTransfer */
        $quoteRequestsRequestAttributesTransfer = $restRequest->getResource()->getAttributes();
        $restUser = $restRequest->getRestUser();
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($restUser->getIdCompanyUser());
        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restUser->getSurrogateIdentifier())
            ->setCompanyUserTransfer($companyUserTransfer)
            ->setCustomerReference($restUser->getNaturalIdentifier());

        return (new QuoteRequestsRequestTransfer())
            ->setCustomer($customerTransfer)
            ->setCartUuid($quoteRequestsRequestAttributesTransfer->getCartUuid())
            ->setMeta($quoteRequestsRequestAttributesTransfer->getMeta());
    }
}
