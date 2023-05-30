<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Reader;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceReader implements ServiceReaderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    protected ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceResponseBuilderInterface
     */
    protected ServiceResponseBuilderInterface $serviceResponseBuilder;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceResponseBuilderInterface $serviceResponseBuilder
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServiceResponseBuilderInterface $serviceResponseBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->serviceResponseBuilder = $serviceResponseBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getServiceCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setPagination($glueRequestTransfer->getPagination())
            ->setSortCollection($glueRequestTransfer->getSortings());

        $serviceTransfers = $this->servicePointFacade
            ->getServiceCollection($serviceCriteriaTransfer)
            ->getServices();

        return $this->serviceResponseBuilder->createServiceResponse($serviceTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getService(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions(
                (new ServiceConditionsTransfer())->addUuid(
                    $glueRequestTransfer->getResourceOrFail()->getIdOrFail(),
                ),
            );

        $serviceTransfers = $this->servicePointFacade
            ->getServiceCollection($serviceCriteriaTransfer)
            ->getServices();

        if ($serviceTransfers->count() === 0) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_ENTITY_NOT_FOUND,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->serviceResponseBuilder
            ->createServiceResponse(
                new ArrayObject([$serviceTransfers->getIterator()->current()]),
            );
    }
}
