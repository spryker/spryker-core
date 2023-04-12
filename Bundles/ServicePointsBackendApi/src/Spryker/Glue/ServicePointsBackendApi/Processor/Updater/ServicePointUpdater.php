<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Updater;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServicePointCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface;
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
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface $servicePointMapper
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface $servicePointResponseBuilder
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServicePointMapperInterface $servicePointMapper,
        ServicePointResponseBuilderInterface $servicePointResponseBuilder
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->servicePointMapper = $servicePointMapper;
        $this->servicePointResponseBuilder = $servicePointResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateServicePoint(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $resourceTransfer = $glueRequestTransfer->getResourceOrFail();

        /**
         * @var \Generated\Shared\Transfer\ApiServicePointsAttributesTransfer|null $apiServicePointsAttributesTransfer
         */
        $apiServicePointsAttributesTransfer = $resourceTransfer->getAttributes();

        if (!$resourceTransfer->getId() || !$apiServicePointsAttributesTransfer) {
            $errorTransfer = (new ErrorTransfer())->setMessage(ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY);

            return $this->servicePointResponseBuilder->createServicePointErrorResponse(
                new ArrayObject([$errorTransfer]),
                $glueRequestTransfer->getLocale(),
            );
        }

        $servicePointTransfer = $this->servicePointMapper->mapApiServicePointsAttributesTransferToServicePointTransfer(
            $apiServicePointsAttributesTransfer,
            new ServicePointTransfer(),
        );
        $servicePointTransfer->setUuid($resourceTransfer->getIdOrFail());

        $servicePointCollectionRequestTransfer = $this->createServicePointCollectionRequestTransfer($servicePointTransfer);
        $servicePointCollectionResponseTransfer = $this->servicePointFacade->updateServicePointCollection($servicePointCollectionRequestTransfer);

        $errorTransfers = $servicePointCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            return $this->servicePointResponseBuilder->createServicePointErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->servicePointResponseBuilder->createServicePointResponse(
            $servicePointCollectionResponseTransfer->getServicePoints(),
        );
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
