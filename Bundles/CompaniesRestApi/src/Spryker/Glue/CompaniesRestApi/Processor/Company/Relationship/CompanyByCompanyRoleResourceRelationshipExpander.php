<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company\Relationship;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CompanyByCompanyRoleResourceRelationshipExpander extends AbstractCompanyResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    protected function findCompanyTransferInPayload(RestResourceInterface $restResource): ?CompanyTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\CompanyRoleTransfer|null $payload
         */
        $payload = $restResource->getPayload();
        if (!$payload || !($payload instanceof CompanyRoleTransfer)) {
            return null;
        }

        return $payload->getCompany();
    }
}
