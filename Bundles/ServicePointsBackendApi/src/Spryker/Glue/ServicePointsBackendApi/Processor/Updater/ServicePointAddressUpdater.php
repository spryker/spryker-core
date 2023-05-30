<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Updater;

use Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointAddressConditionsTransfer;
use Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointAddressUpdater implements ServicePointAddressUpdaterInterface
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
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface
     */
    protected ServicePointAddressMapperInterface $servicePointAddressMapper;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilderInterface
     */
    protected ServicePointAddressResponseBuilderInterface $servicePointAddressResponseBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface $servicePointAddressMapper
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilderInterface $servicePointAddressResponseBuilder
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ErrorResponseBuilderInterface $errorResponseBuilder,
        ServicePointAddressMapperInterface $servicePointAddressMapper,
        ServicePointAddressResponseBuilderInterface $servicePointAddressResponseBuilder
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->errorResponseBuilder = $errorResponseBuilder;
        $this->servicePointAddressMapper = $servicePointAddressMapper;
        $this->servicePointAddressResponseBuilder = $servicePointAddressResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateServicePointAddress(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $apiServicePointAddressesAttributesTransfer = (new ApiServicePointAddressesAttributesTransfer())->fromArray($glueRequestTransfer->getAttributes(), true);

        if (!$this->isRequestBodyValid($glueRequestTransfer, $apiServicePointAddressesAttributesTransfer)) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
                $glueRequestTransfer->getLocale(),
            );
        }

        $glueResourceTransfer = $glueRequestTransfer->getResourceOrFail();
        $servicePointUuid = $this->getParentGlueResourceTransfer($glueRequestTransfer)->getIdOrFail();

        $servicePointAddressTransfer = $this->findServicePointAddress($glueResourceTransfer->getIdOrFail());

        if (!$servicePointAddressTransfer) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ENTITY_NOT_FOUND,
                $glueRequestTransfer->getLocale(),
            );
        }

        $servicePointAddressTransfer = $this->servicePointAddressMapper->mapApiServicePointAddressesAttributesTransferToServicePointAddressTransfer(
            $apiServicePointAddressesAttributesTransfer,
            $servicePointAddressTransfer->setServicePoint((new ServicePointTransfer())->setUuid($servicePointUuid)),
        );

        $servicePointAddressCollectionRequestTransfer = $this->createServicePointAddressCollectionRequestTransfer($servicePointAddressTransfer);
        $servicePointAddressCollectionResponseTransfer = $this->servicePointFacade->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

        $errorTransfers = $servicePointAddressCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            return $this->errorResponseBuilder->createErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->servicePointAddressResponseBuilder->createServicePointAddressResponse(
            $servicePointAddressCollectionResponseTransfer->getServicePointAddresses(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer $apiServicePointAddressesAttributesTransfer
     *
     * @return bool
     */
    protected function isRequestBodyValid(
        GlueRequestTransfer $glueRequestTransfer,
        ApiServicePointAddressesAttributesTransfer $apiServicePointAddressesAttributesTransfer
    ): bool {
        $glueResourceTransfer = $glueRequestTransfer->getResourceOrFail();

        return $glueResourceTransfer->getId()
            && $glueResourceTransfer->getAttributes()
            && $apiServicePointAddressesAttributesTransfer->modifiedToArray();
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer|null
     */
    protected function findServicePointAddress(string $uuid): ?ServicePointAddressTransfer
    {
        $servicePointAddressConditionsTransfer = (new ServicePointAddressConditionsTransfer())
            ->addUuid($uuid);
        $servicePointAddressCriteriaTransfer = (new ServicePointAddressCriteriaTransfer())
            ->setServicePointAddressConditions($servicePointAddressConditionsTransfer);

        $servicePointAddressTransfers = $this->servicePointFacade
            ->getServicePointAddressCollection($servicePointAddressCriteriaTransfer)
            ->getServicePointAddresses();

        return $servicePointAddressTransfers->getIterator()->current();
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
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer
     */
    protected function createServicePointAddressCollectionRequestTransfer(
        ServicePointAddressTransfer $servicePointAddressTransfer
    ): ServicePointAddressCollectionRequestTransfer {
        return (new ServicePointAddressCollectionRequestTransfer())
            ->addServicePointAddress($servicePointAddressTransfer)
            ->setIsTransactional(true);
    }
}
