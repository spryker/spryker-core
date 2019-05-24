<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\RestResponseBuilder;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface CompanyUserRestResponseBuilderInterface
{
    /**
     * @param string $companyUserUuid
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCompanyUsersRestResource(
        string $companyUserUuid,
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): RestResourceInterface;
}
