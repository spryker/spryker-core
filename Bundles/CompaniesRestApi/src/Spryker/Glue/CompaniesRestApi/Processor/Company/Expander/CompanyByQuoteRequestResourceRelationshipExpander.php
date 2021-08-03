<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company\Expander;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CompanyByQuoteRequestResourceRelationshipExpander extends AbstractCompanyResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    protected function findCompanyTransferInPayload(RestResourceInterface $restResource): ?CompanyTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\QuoteRequestTransfer|null $payload
         */
        $payload = $restResource->getPayload();

        if (!$payload || !($payload instanceof QuoteRequestTransfer)) {
            return null;
        }

        $companyUserTransfer = $payload->getCompanyUser();

        if (!$companyUserTransfer || !$companyUserTransfer->getCompany()) {
            return null;
        }

        return $companyUserTransfer->getCompany();
    }
}
