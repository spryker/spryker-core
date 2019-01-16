<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Dependency\Client\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyBusinessUnitAddressReader implements CompanyBusinessUnitAddressReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\CompanyBusinessUnitAddressMapperInterface
     */
    protected $companyBusinessUnitAddressMapperInterface;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Dependency\Client\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface
     */
    protected $companyBusinessUnitAddressClient;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\CompanyBusinessUnitAddressRestResponseBuilderInterface
     */
    protected $companyBusinessUnitAddressRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\CompanyBusinessUnitAddressMapperInterface $companyBusinessUnitAddressMapperInterface
     * @param \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Dependency\Client\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface $companyBusinessUnitAddressClient
     * @param \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\CompanyBusinessUnitAddressRestResponseBuilderInterface $companyBusinessUnitAddressRestResponseBuilder
     */
    public function __construct(
        CompanyBusinessUnitAddressMapperInterface $companyBusinessUnitAddressMapperInterface,
        CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface $companyBusinessUnitAddressClient,
        CompanyBusinessUnitAddressRestResponseBuilderInterface $companyBusinessUnitAddressRestResponseBuilder
    ) {
        $this->companyBusinessUnitAddressMapperInterface = $companyBusinessUnitAddressMapperInterface;
        $this->companyBusinessUnitAddressClient = $companyBusinessUnitAddressClient;
        $this->companyBusinessUnitAddressRestResponseBuilder = $companyBusinessUnitAddressRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyBusinessUnitAddress(RestRequestInterface $restRequest): RestResponseInterface
    {
        $uuid = $restRequest->getResource()->getId();
        if (!$uuid) {
            return $this->companyBusinessUnitAddressRestResponseBuilder->createCompanyBusinessUnitAddressIdMissingError();
        }

        $companyUnitAddressResponseTransfer = $this->companyBusinessUnitAddressClient->findCompanyBusinessUnitAddressByUuid(
            (new CompanyUnitAddressTransfer())->setUuid($uuid)
        );

        if (!$companyUnitAddressResponseTransfer->getIsSuccessful()) {
            return $this->companyBusinessUnitAddressRestResponseBuilder->createCompanyBusinessUnitAddressNotFoundError();
        }

        $restCompanyBusinessUnitAddressAttributesTransfer = $this->companyBusinessUnitAddressMapperInterface
            ->mapCompanyBusinessUnitAddressAttributesTransferToRestCompanyBusinessUnitAddressAttributesTransfer(
                $companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer(),
                new RestCompanyBusinessUnitAddressAttributesTransfer()
            );

        return $this->companyBusinessUnitAddressRestResponseBuilder
            ->createCompanyBusinessUnitAddressRestResponse($uuid, $restCompanyBusinessUnitAddressAttributesTransfer);
    }
}
