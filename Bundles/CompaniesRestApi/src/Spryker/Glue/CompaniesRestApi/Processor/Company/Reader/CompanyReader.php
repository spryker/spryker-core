<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company\Reader;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\RestCompanyAttributesTransfer;
use Spryker\Glue\CompaniesRestApi\Dependency\Client\CompaniesRestApiToCompanyClientInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\RestResponseBuilder\CompanyRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyReader implements CompanyReaderInterface
{
    /**
     * @var \Spryker\Glue\CompaniesRestApi\Dependency\Client\CompaniesRestApiToCompanyClientInterface
     */
    protected $companyClient;

    /**
     * @var \Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface
     */
    protected $companyMapperInterface;

    /**
     * @var \Spryker\Glue\CompaniesRestApi\Processor\Company\RestResponseBuilder\CompanyRestResponseBuilderInterface
     */
    protected $companyRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CompaniesRestApi\Dependency\Client\CompaniesRestApiToCompanyClientInterface $companyClient
     * @param \Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface $companyMapperInterface
     * @param \Spryker\Glue\CompaniesRestApi\Processor\Company\RestResponseBuilder\CompanyRestResponseBuilderInterface $companyRestResponseBuilder
     */
    public function __construct(
        CompaniesRestApiToCompanyClientInterface $companyClient,
        CompanyMapperInterface $companyMapperInterface,
        CompanyRestResponseBuilderInterface $companyRestResponseBuilder
    ) {
        $this->companyClient = $companyClient;
        $this->companyMapperInterface = $companyMapperInterface;
        $this->companyRestResponseBuilder = $companyRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompany(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyUuid = $restRequest->getResource()->getId();
        if (!$companyUuid) {
            return $this->companyRestResponseBuilder->createCompanyIdMissingError();
        }

        $companyResponseTransfer = $this->companyClient->findCompanyByUuid(
            (new CompanyTransfer())->setUuid($companyUuid)
        );

        if (!$companyResponseTransfer->getIsSuccessful()
            || !$this->isCurrentCompanyUserAuthorizedToAccessResource($restRequest, $companyResponseTransfer->getCompanyTransfer())
        ) {
            return $this->companyRestResponseBuilder->createCompanyNotFoundError();
        }

        $restCompanyAttributesTransfer = $this->companyMapperInterface
            ->mapCompanyTransferToRestCompanyAttributesTransfer(
                $companyResponseTransfer->getCompanyTransfer(),
                new RestCompanyAttributesTransfer()
            );

        return $this->companyRestResponseBuilder
            ->createCompanyRestResponse($companyUuid, $restCompanyAttributesTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return bool
     */
    protected function isCurrentCompanyUserAuthorizedToAccessResource(
        RestRequestInterface $restRequest,
        CompanyTransfer $companyTransfer
    ): bool {
        return $restRequest->getRestUser()
            && $restRequest->getRestUser()->getIdCompany()
            && $restRequest->getRestUser()->getIdCompany() === $companyTransfer->getIdCompany();
    }
}
