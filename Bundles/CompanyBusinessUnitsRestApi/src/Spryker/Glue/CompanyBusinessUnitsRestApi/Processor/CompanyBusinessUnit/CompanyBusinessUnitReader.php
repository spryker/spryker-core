<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Dependency\Client\CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapperInterface
     */
    protected $companyBusinessUnitMapperInterface;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApi\Dependency\Client\CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitRestResponseBuilderInterface
     */
    protected $companyBusinessUnitRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapperInterface $companyBusinessUnitMapperInterface
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApi\Dependency\Client\CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitRestResponseBuilderInterface $companyBusinessUnitRestResponseBuilder
     */
    public function __construct(
        CompanyBusinessUnitMapperInterface $companyBusinessUnitMapperInterface,
        CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface $companyBusinessUnitClient,
        CompanyBusinessUnitRestResponseBuilderInterface $companyBusinessUnitRestResponseBuilder
    ) {
        $this->companyBusinessUnitMapperInterface = $companyBusinessUnitMapperInterface;
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
        $this->companyBusinessUnitRestResponseBuilder = $companyBusinessUnitRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyBusinessUnit(RestRequestInterface $restRequest): RestResponseInterface
    {
        $uuid = $restRequest->getResource()->getId();
        if (!$uuid) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitIdMissingError();
        }

        $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitClient->findCompanyBusinessUnitByUuid(
            (new CompanyBusinessUnitTransfer())->setUuid($uuid)
        );

        if (!$companyBusinessUnitResponseTransfer->getIsSuccessful()) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitNotFoundError();
        }

        $restCompanyBusinessUnitAttributesTransfer = $this->companyBusinessUnitMapperInterface
            ->mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
                $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer(),
                new RestCompanyBusinessUnitAttributesTransfer()
            );

        return $this->companyBusinessUnitRestResponseBuilder
            ->createCompanyBusinessUnitRestResponse($uuid, $restCompanyBusinessUnitAttributesTransfer);
    }
}
