<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Reader;

use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
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
    public function getCompanyBusinessUnit(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyBusinessUnitUuid = $restRequest->getResource()->getId();
        if (!$companyBusinessUnitUuid) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitIdMissingError();
        }

        $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitClient->findCompanyBusinessUnitByUuid(
            (new CompanyBusinessUnitTransfer())->setUuid($companyBusinessUnitUuid)
        );

        if (!$companyBusinessUnitResponseTransfer->getIsSuccessful()
            || !$this->isCurrentCompanyUserAuthorizedToAccessCompanyBusinessUnitResource($restRequest, $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer())
        ) {
            return $this->companyBusinessUnitRestResponseBuilder->createCompanyBusinessUnitNotFoundError();
        }

        $restCompanyBusinessUnitAttributesTransfer = $this->mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
            $companyBusinessUnitResponseTransfer,
            new RestCompanyBusinessUnitAttributesTransfer()
        );

        return $this->companyBusinessUnitRestResponseBuilder
            ->createCompanyBusinessUnitRestResponse(
                $companyBusinessUnitUuid,
                $restCompanyBusinessUnitAttributesTransfer,
                $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    protected function mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
        CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer,
        RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
    ): RestCompanyBusinessUnitAttributesTransfer {
        $restCompanyBusinessUnitAttributesTransfer = $this->companyBusinessUnitMapperInterface
            ->mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
                $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer(),
                $restCompanyBusinessUnitAttributesTransfer
            );

        return $this->executeCompanyBusinessUnitMapperPlugins(
            $companyBusinessUnitResponseTransfer,
            $restCompanyBusinessUnitAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    protected function executeCompanyBusinessUnitMapperPlugins(
        CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer,
        RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
    ): RestCompanyBusinessUnitAttributesTransfer {
        foreach ($this->companyBusinessUnitMapperPlugins as $companyBusinessUnitMapperPlugin) {
            $restCompanyBusinessUnitAttributesTransfer = $companyBusinessUnitMapperPlugin->map(
                $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer(),
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
