<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Expander;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CompanyBusinessUnitResourceRelationshipExpander extends AbstractCompanyBusinessUnitResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    protected function findCompanyBusinessUnitTransferInPayload(RestResourceInterface $restResource): ?CompanyBusinessUnitTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\CompanyUserTransfer|null $payload
         */
        $payload = $restResource->getPayload();

        if (
            $payload === null ||
            !($payload instanceof CompanyUserTransfer) ||
            !$payload->getCompanyBusinessUnit()
        ) {
            return null;
        }

        return $payload->getCompanyBusinessUnit();
    }
}
