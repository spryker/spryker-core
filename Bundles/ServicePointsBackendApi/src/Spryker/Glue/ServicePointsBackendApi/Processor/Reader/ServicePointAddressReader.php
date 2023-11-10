<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServicePointAddressConditionsTransfer;
use Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointAddressReader implements ServicePointAddressReaderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    protected ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilderInterface
     */
    protected ServicePointAddressResponseBuilderInterface $servicePointAddressResponseBuilder;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilderInterface $servicePointAddressResponseBuilder
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServicePointAddressResponseBuilderInterface $servicePointAddressResponseBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->servicePointAddressResponseBuilder = $servicePointAddressResponseBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getServicePointAddressCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $servicePointUuid = $this->getParentGlueResourceTransfer($glueRequestTransfer)->getIdOrFail();
        $servicePointTransfer = $this->findServicePointTransfer($servicePointUuid);

        if (!$servicePointTransfer) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND,
                $glueRequestTransfer->getLocale(),
            );
        }

        $servicePointAddressCriteriaTransfer = (new ServicePointAddressCriteriaTransfer())
            ->setServicePointAddressConditions(
                (new ServicePointAddressConditionsTransfer())->addServicePointUuid($servicePointUuid),
            );

        $servicePointAddressTransfers = $this->servicePointFacade
            ->getServicePointAddressCollection($servicePointAddressCriteriaTransfer)
            ->getServicePointAddresses();

        return $this->servicePointAddressResponseBuilder->createServicePointAddressResponse($servicePointAddressTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function getParentGlueResourceTransfer(GlueRequestTransfer $glueRequestTransfer): GlueResourceTransfer
    {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\GlueResourceTransfer> $parentGlueResourceTransfers */
        $parentGlueResourceTransfers = $glueRequestTransfer->getParentResources();
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer|null $parentGlueResourceTransfer */
        $parentGlueResourceTransfer = $parentGlueResourceTransfers->getIterator()->current();

        return $parentGlueResourceTransfer ?: new GlueResourceTransfer();
    }

    /**
     * @param string $servicePointUuid
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer|null
     */
    protected function findServicePointTransfer(string $servicePointUuid): ?ServicePointTransfer
    {
        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())->addUuid($servicePointUuid);
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())->setServicePointConditions($servicePointConditionsTransfer);
        $servicePointCollectionTransfer = $this->servicePointFacade->getServicePointCollection($servicePointCriteriaTransfer);

        return $servicePointCollectionTransfer->getServicePoints()->getIterator()->current();
    }
}
