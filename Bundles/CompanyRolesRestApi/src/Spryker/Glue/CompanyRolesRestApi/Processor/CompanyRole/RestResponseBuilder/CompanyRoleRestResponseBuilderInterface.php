<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\RestResponseBuilder;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\RestCompanyRolesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CompanyRoleRestResponseBuilderInterface
{
    /**
     * @param string $companyRoleUuid
     * @param \Generated\Shared\Transfer\RestCompanyRolesAttributesTransfer $restCompanyRolesAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer|null $companyRoleTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRoleRestResponse(
        string $companyRoleUuid,
        RestCompanyRolesAttributesTransfer $restCompanyRolesAttributesTransfer,
        ?CompanyRoleTransfer $companyRoleTransfer = null
    ): RestResponseInterface;

    /**
     * @param string $companyRoleUuid
     * @param \Generated\Shared\Transfer\RestCompanyRolesAttributesTransfer $restCompanyRolesAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer|null $companyRoleTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCompanyRoleRestResource(
        string $companyRoleUuid,
        RestCompanyRolesAttributesTransfer $restCompanyRolesAttributesTransfer,
        ?CompanyRoleTransfer $companyRoleTransfer = null
    ): RestResourceInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRoleIdMissingError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRoleNotFoundError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createResourceNotImplementedError(): RestResponseInterface;
}
