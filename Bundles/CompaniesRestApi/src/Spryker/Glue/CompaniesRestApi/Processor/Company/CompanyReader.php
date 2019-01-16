<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\RestCompanyAttributesTransfer;
use Spryker\Glue\CompaniesRestApi\Dependency\Client\CompaniesRestApiToCompanyClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyReader implements CompanyReaderInterface
{
    /**
     * @var \Spryker\Glue\CompaniesRestApi\Processor\Company\CompanyMapperInterface
     */
    protected $companyMapperInterface;

    /**
     * @var \Spryker\Glue\CompaniesRestApi\Dependency\Client\CompaniesRestApiToCompanyClientInterface
     */
    protected $companyClient;

    /**
     * @var \Spryker\Glue\CompaniesRestApi\Processor\Company\CompanyRestResponseBuilderInterface
     */
    protected $companyRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CompaniesRestApi\Processor\Company\CompanyMapperInterface $companyMapperInterface
     * @param \Spryker\Glue\CompaniesRestApi\Dependency\Client\CompaniesRestApiToCompanyClientInterface $companyClient
     * @param \Spryker\Glue\CompaniesRestApi\Processor\Company\CompanyRestResponseBuilderInterface $companyRestResponseBuilder
     */
    public function __construct(
        CompanyMapperInterface $companyMapperInterface,
        CompaniesRestApiToCompanyClientInterface $companyClient,
        CompanyRestResponseBuilderInterface $companyRestResponseBuilder
    ) {
        $this->companyMapperInterface = $companyMapperInterface;
        $this->companyClient = $companyClient;
        $this->companyRestResponseBuilder = $companyRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompany(RestRequestInterface $restRequest): RestResponseInterface
    {
        $uuid = $restRequest->getResource()->getId();
        if (!$uuid) {
            return $this->companyRestResponseBuilder->createCompanyIdMissingError();
        }

        $companyResponseTransfer = $this->companyClient->findCompanyByUuid(
            (new CompanyTransfer())->setUuid($uuid)
        );

        if (!$companyResponseTransfer->getIsSuccessful()) {
            return $this->companyRestResponseBuilder->createCompanyNotFoundError();
        }

        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();

        $restCompanyAttributesTransfer = $this->companyMapperInterface
            ->mapCompanyAttributesTransferToRestCompanyAttributesTransfer(
                $companyTransfer,
                new RestCompanyAttributesTransfer()
            );

        return $this->companyRestResponseBuilder
            ->createCompanyRestResponse($uuid, $restCompanyAttributesTransfer);
    }
}
