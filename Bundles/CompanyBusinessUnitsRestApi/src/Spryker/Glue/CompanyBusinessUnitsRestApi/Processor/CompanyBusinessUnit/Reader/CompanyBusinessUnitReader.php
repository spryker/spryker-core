<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Reader;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiConfig;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Dependency\Client\CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\RestResponseBuilder\CompanyBusinessUnitRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
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
            return $this->getUserOwnCompanyBusinessUnit($restRequest);
        }

        return $this->getUserOwnCompanyBusinessUnitByUuid($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getUserOwnCompanyBusinessUnit(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyBusinessUnitTransfer = $this->companyBusinessUnitClient->getCompanyBusinessUnitById(
            (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($restRequest->getRestUser()->getIdCompanyBusinessUnit())
        );

        if (!$companyBusinessUnitTransfer->getUuid()) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitNotFoundError();
        }

        return $this->createResponse($companyBusinessUnitTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getUserOwnCompanyBusinessUnitByUuid(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitClient->findCompanyBusinessUnitByUuid(
            (new CompanyBusinessUnitTransfer())->setUuid($restRequest->getResource()->getId())
        );

        if (!$companyBusinessUnitResponseTransfer->getIsSuccessful()
            || !$this->isCurrentCompanyUserAuthorizedToAccessCompanyBusinessUnitResource($restRequest, $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer())) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitNotFoundError();
        }

        return $this->createResponse($companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer());
    }

    /**
     * @param string $companyIdentifier
     *
     * @return bool
     */
    protected function isCurrentUserResourceIdentifier(string $companyIdentifier): bool
    {
        return $companyIdentifier === CompanyBusinessUnitsRestApiConfig::CURRENT_USER_COLLECTION_IDENTIFIER;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createResponse(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): RestResponseInterface
    {
        $restCompanyBusinessUnitAttributesTransfer = $this->mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
            $companyBusinessUnitTransfer,
            new RestCompanyBusinessUnitAttributesTransfer()
        );

        return $this->companyBusinessUnitRestResponseBuilder
            ->createCompanyBusinessUnitRestResponse(
                $companyBusinessUnitTransfer->getUuid(),
                $restCompanyBusinessUnitAttributesTransfer,
                $companyBusinessUnitTransfer
            );
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
}
