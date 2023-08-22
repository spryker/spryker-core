<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServicesRequestBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceUpdater implements ServiceUpdaterInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    protected ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface
     */
    protected ServiceMapperInterface $serviceMapper;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceResponseBuilderInterface
     */
    protected ServiceResponseBuilderInterface $serviceResponseBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface $serviceMapper
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceResponseBuilderInterface $serviceResponseBuilder
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ErrorResponseBuilderInterface $errorResponseBuilder,
        ServiceMapperInterface $serviceMapper,
        ServiceResponseBuilderInterface $serviceResponseBuilder
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->errorResponseBuilder = $errorResponseBuilder;
        $this->serviceMapper = $serviceMapper;
        $this->serviceResponseBuilder = $serviceResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicesRequestBackendApiAttributesTransfer $servicesRequestBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateService(
        ServicesRequestBackendApiAttributesTransfer $servicesRequestBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        if (!$this->isRequestBodyValid($glueRequestTransfer, $servicesRequestBackendApiAttributesTransfer)) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
                $glueRequestTransfer->getLocale(),
            );
        }

        $serviceTransfer = $this->findService($glueRequestTransfer->getResourceOrFail()->getIdOrFail());
        if (!$serviceTransfer) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_ENTITY_NOT_FOUND,
                $glueRequestTransfer->getLocale(),
            );
        }

        $serviceTransfer = $this->serviceMapper->mapServicesRequestBackendApiAttributesTransferToServiceTransfer(
            $servicesRequestBackendApiAttributesTransfer,
            $serviceTransfer,
        );

        $serviceCollectionRequestTransfer = $this->createServiceCollectionRequestTransfer($serviceTransfer);
        $serviceCollectionResponseTransfer = $this->servicePointFacade->updateServiceCollection($serviceCollectionRequestTransfer);

        $errorTransfers = $serviceCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            return $this->errorResponseBuilder->createErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->serviceResponseBuilder->createServiceResponse(
            $serviceCollectionResponseTransfer->getServices(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\ServicesRequestBackendApiAttributesTransfer $servicesRequestBackendApiAttributesTransfer
     *
     * @return bool
     */
    protected function isRequestBodyValid(
        GlueRequestTransfer $glueRequestTransfer,
        ServicesRequestBackendApiAttributesTransfer $servicesRequestBackendApiAttributesTransfer
    ): bool {
        $glueResourceTransfer = $glueRequestTransfer->getResourceOrFail();

        return $glueResourceTransfer->getId()
            && $glueResourceTransfer->getAttributes()
            && $servicesRequestBackendApiAttributesTransfer->modifiedToArray();
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer|null
     */
    protected function findService(string $uuid): ?ServiceTransfer
    {
        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addUuid($uuid);
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        $serviceTransfers = $this->servicePointFacade
            ->getServiceCollection($serviceCriteriaTransfer)
            ->getServices();

        return $serviceTransfers->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionRequestTransfer
     */
    protected function createServiceCollectionRequestTransfer(
        ServiceTransfer $serviceTransfer
    ): ServiceCollectionRequestTransfer {
        return (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);
    }
}
