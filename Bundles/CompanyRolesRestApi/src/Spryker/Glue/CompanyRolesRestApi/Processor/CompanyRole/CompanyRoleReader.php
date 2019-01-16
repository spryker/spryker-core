<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer;
use Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyRoleReader implements CompanyRoleReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleMapperInterface
     */
    protected $companyRoleMapperInterface;

    /**
     * @var \Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientInterface
     */
    protected $companyRoleClient;

    /**
     * @var \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleRestResponseBuilderInterface
     */
    protected $companyRoleRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleMapperInterface $companyRoleMapperInterface
     * @param \Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientInterface $companyRoleClient
     * @param \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleRestResponseBuilderInterface $companyRoleRestResponseBuilder
     */
    public function __construct(
        CompanyRoleMapperInterface $companyRoleMapperInterface,
        CompanyRolesRestApiToCompanyRoleClientInterface $companyRoleClient,
        CompanyRoleRestResponseBuilderInterface $companyRoleRestResponseBuilder
    ) {
        $this->companyRoleMapperInterface = $companyRoleMapperInterface;
        $this->companyRoleClient = $companyRoleClient;
        $this->companyRoleRestResponseBuilder = $companyRoleRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyRole(RestRequestInterface $restRequest): RestResponseInterface
    {
        $uuid = $restRequest->getResource()->getId();
        if (!$uuid) {
            return $this->companyRoleRestResponseBuilder->createCompanyRoleIdMissingError();
        }

        $companyRoleResponseTransfer = $this->companyRoleClient->findCompanyRoleByUuid(
            (new CompanyRoleTransfer())->setUuid($uuid)
        );

        if (!$companyRoleResponseTransfer->getIsSuccessful()) {
            return $this->companyRoleRestResponseBuilder->createCompanyRoleNotFoundError();
        }

        $companyRoleTransfer = $companyRoleResponseTransfer->getCompanyRoleTransfer();

        $restCompanyRoleAttributesTransfer = $this->companyRoleMapperInterface
            ->mapCompanyRoleAttributesTransferToRestCompanyRoleAttributesTransfer(
                $companyRoleTransfer,
                new RestCompanyRoleAttributesTransfer()
            );

        return $this->companyRoleRestResponseBuilder
            ->createCompanyRoleRestResponse($uuid, $restCompanyRoleAttributesTransfer);
    }
}
