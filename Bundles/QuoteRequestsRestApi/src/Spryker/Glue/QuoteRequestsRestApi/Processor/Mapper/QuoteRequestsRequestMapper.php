<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class QuoteRequestsRequestMapper implements QuoteRequestsRequestMapperInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function mapRestRequestToQuoteRequestTransfer(
        RestRequestInterface $restRequest,
        QuoteTransfer $quoteTransfer
    ): QuoteRequestTransfer {
        /** @var \Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer $quoteRequestsRequestAttributesTransfer */
        $quoteRequestsRequestAttributesTransfer = $restRequest->getResource()->getAttributes();

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());

        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setMetadata($quoteRequestsRequestAttributesTransfer->getMeta())
            ->setQuote($quoteTransfer);

        return (new QuoteRequestTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setLatestVersion($quoteRequestVersionTransfer);
    }
}
