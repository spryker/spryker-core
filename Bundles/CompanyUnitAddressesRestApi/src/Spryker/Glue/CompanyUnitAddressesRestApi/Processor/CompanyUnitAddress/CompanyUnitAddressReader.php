<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer;
use Spryker\Glue\CompanyUnitAddressesRestApi\Dependency\Client\CompanyUnitAddressesRestApiToCompanyUnitAddressClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyUnitAddressReader implements CompanyUnitAddressReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressMapperInterface
     */
    protected $companyUnitAddressMapperInterface;

    /**
     * @var \Spryker\Glue\CompanyUnitAddressesRestApi\Dependency\Client\CompanyUnitAddressesRestApiToCompanyUnitAddressClientInterface
     */
    protected $companyUnitAddressClient;

    /**
     * @var \Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressRestResponseBuilderInterface
     */
    protected $companyUnitAddressRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressMapperInterface $companyUnitAddressMapperInterface
     * @param \Spryker\Glue\CompanyUnitAddressesRestApi\Dependency\Client\CompanyUnitAddressesRestApiToCompanyUnitAddressClientInterface $companyUnitAddressClient
     * @param \Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressRestResponseBuilderInterface $companyUnitAddressRestResponseBuilder
     */
    public function __construct(
        CompanyUnitAddressMapperInterface $companyUnitAddressMapperInterface,
        CompanyUnitAddressesRestApiToCompanyUnitAddressClientInterface $companyUnitAddressClient,
        CompanyUnitAddressRestResponseBuilderInterface $companyUnitAddressRestResponseBuilder
    ) {
        $this->companyUnitAddressMapperInterface = $companyUnitAddressMapperInterface;
        $this->companyUnitAddressClient = $companyUnitAddressClient;
        $this->companyUnitAddressRestResponseBuilder = $companyUnitAddressRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyUnitAddress(RestRequestInterface $restRequest): RestResponseInterface
    {
        $uuid = $restRequest->getResource()->getId();
        if (!$uuid) {
            return $this->companyUnitAddressRestResponseBuilder->createCompanyUnitAddressIdMissingError();
        }

        $companyUnitAddressResponseTransfer = $this->companyUnitAddressClient->findCompanyUnitAddressByUuid(
            (new CompanyUnitAddressTransfer())->setUuid($uuid)
        );

        if (!$companyUnitAddressResponseTransfer->getIsSuccessful()) {
            return $this->companyUnitAddressRestResponseBuilder->createCompanyUnitAddressNotFoundError();
        }

        $restCompanyUnitAddressAttributesTransfer = $this->companyUnitAddressMapperInterface
            ->mapCompanyUnitAddressAttributesTransferToRestCompanyUnitAddressAttributesTransfer(
                $companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer(),
                new RestCompanyUnitAddressAttributesTransfer()
            );

        return $this->companyUnitAddressRestResponseBuilder
            ->createCompanyUnitAddressRestResponse($uuid, $restCompanyUnitAddressAttributesTransfer);
    }
}
