<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Builder;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig;

class QuoteRequestFilterBuilder implements QuoteRequestFilterBuilderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param bool $isParent
     *
     * @return \Generated\Shared\Transfer\QuoteRequestFilterTransfer|null
     */
    public function buildFilterFromRequest(RestRequestInterface $restRequest, bool $isParent = false): ?QuoteRequestFilterTransfer
    {
        $quoteRequestReference = $restRequest->getResource()->getId();
        $parentResource = $restRequest->findParentResourceByType(QuoteRequestsRestApiConfig::RESOURCE_QUOTE_REQUESTS);

        if ($isParent) {
            if (!$parentResource || $parentResource->getId() === null) {
                return null;
            }

            $quoteRequestReference = $parentResource->getId();
        }

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference);

        if ($restRequest->getRestUser()) {
            $companyUserTransfer = (new CompanyUserTransfer())
                ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());

            $quoteRequestFilterTransfer
                ->setCompanyUser($companyUserTransfer)
                ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());
        }

        return $quoteRequestFilterTransfer;
    }
}
