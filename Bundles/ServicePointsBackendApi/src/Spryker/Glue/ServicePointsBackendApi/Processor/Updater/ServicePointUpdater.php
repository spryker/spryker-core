<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServicePointCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointUpdater implements ServicePointUpdaterInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    protected ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface
     */
    protected ServicePointResponseBuilderInterface $servicePointResponseBuilder;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface
     */
    protected ServicePointMapperInterface $servicePointMapper;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface $servicePointMapper
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface $servicePointResponseBuilder
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServicePointMapperInterface $servicePointMapper,
        ServicePointResponseBuilderInterface $servicePointResponseBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->servicePointMapper = $servicePointMapper;
        $this->servicePointResponseBuilder = $servicePointResponseBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateServicePoint(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if (!$this->isRequestedEntityValid($glueRequestTransfer)) {
            return $this->errorResponseBuilder
                ->createErrorResponseFromErrorMessage(
                    ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
                    $glueRequestTransfer->getLocale(),
                );
        }

        $glueResourceTransfer = $glueRequestTransfer->getResourceOrFail();

        /**
         * @var \Generated\Shared\Transfer\ApiServicePointsAttributesTransfer $apiServicePointsAttributesTransfer
         */
        $apiServicePointsAttributesTransfer = $glueResourceTransfer->getAttributesOrFail();

        $servicePointTransfer = $this->findServicePoint($glueResourceTransfer->getIdOrFail());

        if (!$servicePointTransfer) {
            return $this->errorResponseBuilder
                ->createErrorResponseFromErrorMessage(
                    ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND,
                    $glueRequestTransfer->getLocale(),
                );
        }

        $servicePointTransfer = $this->servicePointMapper->mapApiServicePointsAttributesTransferToServicePointTransfer(
            $apiServicePointsAttributesTransfer,
            $servicePointTransfer,
        );

        $servicePointCollectionRequestTransfer = $this->createServicePointCollectionRequestTransfer($servicePointTransfer);
        $servicePointCollectionResponseTransfer = $this->servicePointFacade->updateServicePointCollection($servicePointCollectionRequestTransfer);

        $errorTransfers = $servicePointCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            return $this->errorResponseBuilder->createErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->servicePointResponseBuilder->createServicePointResponse(
            $servicePointCollectionResponseTransfer->getServicePoints(),
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
            && array_filter($glueResourceTransfer->getAttributesOrFail()->modifiedToArray(), function ($value) {
                return $value !== null;
            });
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer|null
     */
    protected function findServicePoint(string $uuid): ?ServicePointTransfer
    {
        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addUuid($uuid)
            ->setWithStoreRelations(true);
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        $servicePointTransfers = $this->servicePointFacade
            ->getServicePointCollection($servicePointCriteriaTransfer)
            ->getServicePoints();

        return $servicePointTransfers->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionRequestTransfer
     */
    protected function createServicePointCollectionRequestTransfer(
        ServicePointTransfer $servicePointTransfer
    ): ServicePointCollectionRequestTransfer {
        return (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);
    }
}
