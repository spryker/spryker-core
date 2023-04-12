<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointReader implements ServicePointReaderInterface
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
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface $servicePointResponseBuilder
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServicePointResponseBuilderInterface $servicePointResponseBuilder
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->servicePointResponseBuilder = $servicePointResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getServicePointCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setPagination($glueRequestTransfer->getPagination())
            ->setSortCollection($glueRequestTransfer->getSortings())
            ->setServicePointConditions((new ServicePointConditionsTransfer())->setWithStoreRelations(true));

        $servicePointTransfers = $this->servicePointFacade
            ->getServicePointCollection($servicePointCriteriaTransfer)
            ->getServicePoints();

        return $this->servicePointResponseBuilder->createServicePointResponse($servicePointTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getServicePoint(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->addUuid($glueRequestTransfer->getResourceOrFail()->getIdOrFail())
            ->setWithStoreRelations(true);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        $servicePointTransfers = $this->servicePointFacade
            ->getServicePointCollection($servicePointCriteriaTransfer)
            ->getServicePoints();

        if ($servicePointTransfers->count() === 1) {
            return $this->servicePointResponseBuilder->createServicePointResponse(
                new ArrayObject([$servicePointTransfers->getIterator()->current()]),
            );
        }

        $errorTransfer = (new ErrorTransfer())->setMessage(ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND);

        return $this->servicePointResponseBuilder->createServicePointErrorResponse(
            new ArrayObject([$errorTransfer]),
            $glueRequestTransfer->getLocale(),
        );
    }
}
