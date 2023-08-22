<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Creator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServiceTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceTypeResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceTypeCreator implements ServiceTypeCreatorInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    protected ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceTypeResponseBuilderInterface
     */
    protected ServiceTypeResponseBuilderInterface $serviceTypeResponseBuilder;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface
     */
    protected ServiceTypeMapperInterface $serviceTypeMapper;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface $serviceTypeMapper
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceTypeResponseBuilderInterface $serviceTypeResponseBuilder
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServiceTypeMapperInterface $serviceTypeMapper,
        ServiceTypeResponseBuilderInterface $serviceTypeResponseBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->serviceTypeMapper = $serviceTypeMapper;
        $this->serviceTypeResponseBuilder = $serviceTypeResponseBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createServiceType(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ServiceTypesBackendApiAttributesTransfer|null $serviceTypesBackendApiAttributesTransfer */
        $serviceTypesBackendApiAttributesTransfer = $glueRequestTransfer->getResourceOrFail()->getAttributes();

        if (!$serviceTypesBackendApiAttributesTransfer) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
                $glueRequestTransfer->getLocale(),
            );
        }

        $serviceTypeTransfer = $this->serviceTypeMapper->mapServiceTypesBackendApiAttributesTransferToServiceTypeTransfer(
            $serviceTypesBackendApiAttributesTransfer,
            new ServiceTypeTransfer(),
        );

        $serviceTypeCollectionRequestTransfer = $this->createServiceTypeCollectionRequestTransfer($serviceTypeTransfer);
        $serviceTypeCollectionResponseTransfer = $this->servicePointFacade->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        $errorTransfers = $serviceTypeCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            return $this->errorResponseBuilder->createErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->serviceTypeResponseBuilder->createServiceTypeResponse(
            $serviceTypeCollectionResponseTransfer->getServiceTypes(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionRequestTransfer
     */
    protected function createServiceTypeCollectionRequestTransfer(
        ServiceTypeTransfer $serviceTypeTransfer
    ): ServiceTypeCollectionRequestTransfer {
        return (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);
    }
}
