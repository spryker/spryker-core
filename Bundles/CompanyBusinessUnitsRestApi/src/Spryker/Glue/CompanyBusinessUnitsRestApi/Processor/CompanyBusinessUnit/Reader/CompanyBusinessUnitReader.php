<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Reader;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Dependency\Client\CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\RestResponseBuilder\CompanyBusinessUnitRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
    protected const CURRENT_USER_COLLECTION_IDENTIFIER = 'mine';

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface
     */
    protected $companyBusinessUnitMapperInterface;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApi\Dependency\Client\CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\RestResponseBuilder\CompanyBusinessUnitRestResponseBuilderInterface
     */
    protected $companyBusinessUnitRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitMapperPluginInterface[]
     */
    protected $companyBusinessUnitMapperPlugins;

    /**
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface $companyBusinessUnitMapperInterface
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApi\Dependency\Client\CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\RestResponseBuilder\CompanyBusinessUnitRestResponseBuilderInterface $companyBusinessUnitRestResponseBuilder
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitMapperPluginInterface[] $companyBusinessUnitMapperPlugins
     */
    public function __construct(
        CompanyBusinessUnitMapperInterface $companyBusinessUnitMapperInterface,
        CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface $companyBusinessUnitClient,
        CompanyBusinessUnitRestResponseBuilderInterface $companyBusinessUnitRestResponseBuilder,
        array $companyBusinessUnitMapperPlugins
    ) {
        $this->companyBusinessUnitMapperInterface = $companyBusinessUnitMapperInterface;
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
        $this->companyBusinessUnitRestResponseBuilder = $companyBusinessUnitRestResponseBuilder;
        $this->companyBusinessUnitMapperPlugins = $companyBusinessUnitMapperPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCurrentUserCompanyBusinessUnit(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getResource()->getId()) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitIdMissingError();
        }

        if ($this->isCurrentUserResourceIdentifier($restRequest->getResource()->getId())) {
            return $this->getCurrentUserCompanyBusinessUnits($restRequest);
        }

        return $this->getCurrentUserCompanyBusinessUnitByUuid($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCurrentUserCompanyBusinessUnits(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getRestUser()->getIdCompany()) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitIdMissingError();
        }

        $companyBusinessUnitCollection = $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($restRequest->getRestUser()->getIdCompany())
        );

        if (!$companyBusinessUnitCollection->getCompanyBusinessUnits()->count()) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitNotFoundError();
        }

        return $this->createCompanyBusinessUnitCollectionResponse($companyBusinessUnitCollection);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCurrentUserCompanyBusinessUnitByUuid(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitClient->findCompanyBusinessUnitByUuid(
            (new CompanyBusinessUnitTransfer())->setUuid($restRequest->getResource()->getId())
        );

        if (!$companyBusinessUnitResponseTransfer->getIsSuccessful()
            || !$this->isCurrentCompanyUserAuthorizedToAccessCompanyBusinessUnitResource($restRequest, $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer())) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitNotFoundError();
        }

        $restCompanyBusinessUnitAttributesTransfer = $this->mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
            $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer(),
            new RestCompanyBusinessUnitAttributesTransfer()
        );

        return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitRestResponse(
            $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getUuid(),
            $restCompanyBusinessUnitAttributesTransfer,
            $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()
        );
    }

    /**
     * @param string $resourceIdentifier
     *
     * @return bool
     */
    protected function isCurrentUserResourceIdentifier(string $resourceIdentifier): bool
    {
        return $resourceIdentifier === static::CURRENT_USER_COLLECTION_IDENTIFIER;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCompanyBusinessUnitCollectionResponse(CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer): RestResponseInterface
    {
        $companyBusinessUnitRestResourceCollection = [];

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitRestResourceCollection[] = $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitRestResource(
                $companyBusinessUnitTransfer->getUuid(),
                $this->getRestCompanyBusinessUnitAttributesTransfer($companyBusinessUnitTransfer),
                $companyBusinessUnitTransfer
            );
        }

        return $this->companyBusinessUnitRestResponseBuilder
            ->createCompanyBusinessUnitCollectionRestResponse($companyBusinessUnitRestResourceCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    protected function mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
    ): RestCompanyBusinessUnitAttributesTransfer {
        $restCompanyBusinessUnitAttributesTransfer = $this->companyBusinessUnitMapperInterface
            ->mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
                $companyBusinessUnitTransfer,
                $restCompanyBusinessUnitAttributesTransfer
            );

        return $this->executeCompanyBusinessUnitMapperPlugins(
            $companyBusinessUnitTransfer,
            $restCompanyBusinessUnitAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    protected function executeCompanyBusinessUnitMapperPlugins(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
    ): RestCompanyBusinessUnitAttributesTransfer {
        foreach ($this->companyBusinessUnitMapperPlugins as $companyBusinessUnitMapperPlugin) {
            $restCompanyBusinessUnitAttributesTransfer = $companyBusinessUnitMapperPlugin->map(
                $companyBusinessUnitTransfer,
                $restCompanyBusinessUnitAttributesTransfer
            );
        }

        return $restCompanyBusinessUnitAttributesTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    protected function isCurrentCompanyUserAuthorizedToAccessCompanyBusinessUnitResource(
        RestRequestInterface $restRequest,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): bool {
        return $restRequest->getRestUser()
            && $restRequest->getRestUser()->getIdCompany()
            && $restRequest->getRestUser()->getIdCompany() === $companyBusinessUnitTransfer->getFkCompany();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    protected function getRestCompanyBusinessUnitAttributesTransfer(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): RestCompanyBusinessUnitAttributesTransfer
    {
        return $this->mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
            $companyBusinessUnitTransfer,
            new RestCompanyBusinessUnitAttributesTransfer()
        );
    }
}
