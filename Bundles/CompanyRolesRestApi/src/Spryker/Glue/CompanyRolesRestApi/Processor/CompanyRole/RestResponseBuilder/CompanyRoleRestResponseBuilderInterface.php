<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\RestResponseBuilder;

use Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CompanyRoleRestResponseBuilderInterface
{
    /**
     * @param string $companyRoleUuid
     * @param \Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer $restCompanyRoleAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRoleRestResponse(
        string $companyRoleUuid,
        RestCompanyRoleAttributesTransfer $restCompanyRoleAttributesTransfer
    ): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRoleIdMissingError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRoleNotFoundError(): RestResponseInterface;
}
