<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServiceTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceTypeResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceTypeUpdater implements ServiceTypeUpdaterInterface
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
    public function updateServiceType(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if (!$this->isRequestedEntityValid($glueRequestTransfer)) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
                $glueRequestTransfer->getLocale(),
            );
        }

        $glueResourceTransfer = $glueRequestTransfer->getResourceOrFail();

        /**
         * @var \Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer $apiServiceTypesAttributesTransfer
         */
        $apiServiceTypesAttributesTransfer = $glueResourceTransfer->getAttributesOrFail();
        $serviceTypeTransfer = $this->findServiceType($glueResourceTransfer->getIdOrFail());
        if (!$serviceTypeTransfer) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND,
                $glueRequestTransfer->getLocale(),
            );
        }

        $serviceTypeTransfer = $this->serviceTypeMapper->mapApiServiceTypesAttributesTransferToServiceTypeTransfer(
            $apiServiceTypesAttributesTransfer,
            $serviceTypeTransfer,
        );

        $serviceTypeCollectionRequestTransfer = $this->createServiceTypeCollectionRequestTransfer($serviceTypeTransfer);
        $serviceTypeCollectionResponseTransfer = $this->servicePointFacade->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);

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
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function isRequestedEntityValid(GlueRequestTransfer $glueRequestTransfer): bool
    {
        $glueResourceTransfer = $glueRequestTransfer->getResourceOrFail();

        return $glueResourceTransfer->getId()
            && $glueResourceTransfer->getAttributes()
            && array_filter($glueResourceTransfer->getAttributesOrFail()->modifiedToArray());
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer|null
     */
    protected function findServiceType(string $uuid): ?ServiceTypeTransfer
    {
        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())->addUuid($uuid);
        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions($serviceTypeConditionsTransfer);

        $serviceTypeTransfers = $this->servicePointFacade
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer)
            ->getServiceTypes();

        return $serviceTypeTransfers->getIterator()->current();
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
