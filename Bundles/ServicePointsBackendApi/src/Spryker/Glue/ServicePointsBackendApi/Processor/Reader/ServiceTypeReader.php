<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Reader;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceTypeResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceTypeReader implements ServiceTypeReaderInterface
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
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceTypeResponseBuilderInterface $serviceTypeResponseBuilder
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServiceTypeResponseBuilderInterface $serviceTypeResponseBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->serviceTypeResponseBuilder = $serviceTypeResponseBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getServiceTypeCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setPagination($glueRequestTransfer->getPagination())
            ->setSortCollection($glueRequestTransfer->getSortings());

        $serviceTypeTransfers = $this->servicePointFacade
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer)
            ->getServiceTypes();

        return $this->serviceTypeResponseBuilder->createServiceTypeResponse($serviceTypeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getServiceType(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())
            ->addUuid($glueRequestTransfer->getResourceOrFail()->getIdOrFail());

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions($serviceTypeConditionsTransfer);

        $serviceTypeTransfers = $this->servicePointFacade
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer)
            ->getServiceTypes();

        if ($serviceTypeTransfers->count() === 1) {
            return $this->serviceTypeResponseBuilder->createServiceTypeResponse(
                new ArrayObject([$serviceTypeTransfers->getIterator()->current()]),
            );
        }

        return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
            ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND,
            $glueRequestTransfer->getLocale(),
        );
    }
}
