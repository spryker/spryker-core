<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\RestResponseBuilder;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyRoleRestResponseBuilder implements CompanyRoleRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param string $companyRoleUuid
     * @param \Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer $restCompanyRoleAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer|null $companyRoleTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRoleRestResponse(
        string $companyRoleUuid,
        RestCompanyRoleAttributesTransfer $restCompanyRoleAttributesTransfer,
        ?CompanyRoleTransfer $companyRoleTransfer = null
    ): RestResponseInterface {
        $companyRoleRestResource = $this->createCompanyRoleRestResource(
            $companyRoleUuid,
            $restCompanyRoleAttributesTransfer,
            $companyRoleTransfer
        );

        return $this->restResourceBuilder->createRestResponse()
            ->addResource($companyRoleRestResource);
    }

    /**
     * @param string $companyRoleUuid
     * @param \Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer $restCompanyRoleAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer|null $companyRoleTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCompanyRoleRestResource(
        string $companyRoleUuid,
        RestCompanyRoleAttributesTransfer $restCompanyRoleAttributesTransfer,
        ?CompanyRoleTransfer $companyRoleTransfer = null
    ): RestResourceInterface {
        $restResource = $this->restResourceBuilder->createRestResource(
            CompanyRolesRestApiConfig::RESOURCE_COMPANY_ROLES,
            $companyRoleUuid,
            $restCompanyRoleAttributesTransfer
        );

        if (!$companyRoleTransfer) {
            return $restResource;
        }

        $restResource->setPayload($companyRoleTransfer);

        return $restResource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRoleIdMissingError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CompanyRolesRestApiConfig::RESPONSE_CODE_COMPANY_ROLE_ID_IS_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CompanyRolesRestApiConfig::RESPONSE_DETAIL_COMPANY_ROLE_ID_IS_MISSING);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRoleNotFoundError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CompanyRolesRestApiConfig::RESPONSE_CODE_COMPANY_ROLE_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CompanyRolesRestApiConfig::RESPONSE_DETAIL_COMPANY_ROLE_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createResourceNotImplementedError(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_IMPLEMENTED)
            ->setDetail(CompanyRolesRestApiConfig::RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError($restErrorMessageTransfer);
    }
}
