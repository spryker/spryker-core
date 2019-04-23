<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Reader;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiConfig;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Dependency\Client\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Mapper\CompanyBusinessUnitAddressMapperInterface;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\RestResponseBuilder\CompanyBusinessUnitAddressRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyBusinessUnitAddressReader implements CompanyBusinessUnitAddressReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Dependency\Client\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface
     */
    protected $companyBusinessUnitAddressClient;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Mapper\CompanyBusinessUnitAddressMapperInterface
     */
    protected $companyBusinessUnitAddressMapperInterface;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\RestResponseBuilder\CompanyBusinessUnitAddressRestResponseBuilderInterface
     */
    protected $companyBusinessUnitAddressRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Dependency\Client\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface $companyBusinessUnitAddressClient
     * @param \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Mapper\CompanyBusinessUnitAddressMapperInterface $companyBusinessUnitAddressMapperInterface
     * @param \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\RestResponseBuilder\CompanyBusinessUnitAddressRestResponseBuilderInterface $companyBusinessUnitAddressRestResponseBuilder
     */
    public function __construct(
        CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface $companyBusinessUnitAddressClient,
        CompanyBusinessUnitAddressMapperInterface $companyBusinessUnitAddressMapperInterface,
        CompanyBusinessUnitAddressRestResponseBuilderInterface $companyBusinessUnitAddressRestResponseBuilder
    ) {
        $this->companyBusinessUnitAddressClient = $companyBusinessUnitAddressClient;
        $this->companyBusinessUnitAddressMapperInterface = $companyBusinessUnitAddressMapperInterface;
        $this->companyBusinessUnitAddressRestResponseBuilder = $companyBusinessUnitAddressRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCurrentUserCompanyBusinessUnitAddress(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getResource()->getId()) {
            return $this->companyBusinessUnitAddressRestResponseBuilder->createCompanyBusinessUnitAddressIdMissingError();
        }

        if ($this->isCurrentUserResourceIdentifier($restRequest)) {
            return $this->getCurrentUserCompanyBusinessUnitAddresses($restRequest);
        }

        return $this->getCurrentUserCompanyBusinessUnitAddressByUuid($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCurrentUserCompanyBusinessUnitAddressByUuid(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyUnitAddressResponseTransfer = $this->companyBusinessUnitAddressClient->findCompanyBusinessUnitAddressByUuid(
            (new CompanyUnitAddressTransfer())->setUuid($restRequest->getResource()->getId())
        );

        if (!$companyUnitAddressResponseTransfer->getIsSuccessful()
            || !$this->isCurrentCompanyUserAuthorizedToAccessCompanyBusinessUnitAddressResource($restRequest, $companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer())
        ) {
            return $this->companyBusinessUnitAddressRestResponseBuilder->createCompanyBusinessUnitAddressNotFoundError();
        }

        $restCompanyBusinessUnitAddressesAttributesTransfer = $this->companyBusinessUnitAddressMapperInterface
            ->mapCompanyUnitAddressTransferToRestCompanyBusinessUnitAddressesAttributesTransfer(
                $companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer(),
                new RestCompanyBusinessUnitAddressesAttributesTransfer()
            );

        return $this->companyBusinessUnitAddressRestResponseBuilder
            ->createCompanyBusinessUnitAddressRestResponse(
                $companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getUuid(),
                $restCompanyBusinessUnitAddressesAttributesTransfer
            );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCurrentUserCompanyBusinessUnitAddresses(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getRestUser()->getIdCompany()) {
            return $this->companyBusinessUnitAddressRestResponseBuilder->createCompanyBusinessUnitAddressIdMissingError();
        }

        $companyUnitAddressCollectionTransfer = $this->companyBusinessUnitAddressClient->getCompanyUnitAddressCollection(
            (new CompanyUnitAddressCriteriaFilterTransfer())->setIdCompany($restRequest->getRestUser()->getIdCompany())
        );

        if (!$companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->count()) {
            return $this->companyBusinessUnitAddressRestResponseBuilder->createCompanyBusinessUnitAddressNotFoundError();
        }

        return $this->createResponse($companyUnitAddressCollectionTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return bool
     */
    protected function isCurrentCompanyUserAuthorizedToAccessCompanyBusinessUnitAddressResource(
        RestRequestInterface $restRequest,
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): bool {
        return $restRequest->getRestUser()
            && $restRequest->getRestUser()->getIdCompany()
            && $restRequest->getRestUser()->getIdCompany() === $companyUnitAddressTransfer->getFkCompany();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createResponse(CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer): RestResponseInterface
    {
        $companyBusinessUnitResponse = $this->companyBusinessUnitAddressRestResponseBuilder->createEmptyCompanyBusinessUnitAddressRestResponse();

        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $restCompanyBusinessUnitAddressesAttributesTransfer = $this->companyBusinessUnitAddressMapperInterface
                ->mapCompanyUnitAddressTransferToRestCompanyBusinessUnitAddressesAttributesTransfer(
                    $companyUnitAddressTransfer,
                    new RestCompanyBusinessUnitAddressesAttributesTransfer()
                );

            $companyBusinessUnitResponse->addResource(
                $this->companyBusinessUnitAddressRestResponseBuilder->createCompanyBusinessUnitAddressRestResource(
                    $companyUnitAddressTransfer->getUuid(),
                    $restCompanyBusinessUnitAddressesAttributesTransfer
                )
            );
        }

        return $companyBusinessUnitResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isCurrentUserResourceIdentifier(RestRequestInterface $restRequest): bool
    {
        return $restRequest->getResource()->getId() === CompanyBusinessUnitAddressesRestApiConfig::CURRENT_USER_COLLECTION_IDENTIFIER;
    }
}
