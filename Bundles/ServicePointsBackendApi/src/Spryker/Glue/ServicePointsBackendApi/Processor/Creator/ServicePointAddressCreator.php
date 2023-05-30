<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Creator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointAddressCreator implements ServicePointAddressCreatorInterface
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
    public function createServicePointAddress(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer|null $apiServicePointAddressesAttributesTransfer */
        $apiServicePointAddressesAttributesTransfer = $glueRequestTransfer->getResourceOrFail()->getAttributes();

        if (!$apiServicePointAddressesAttributesTransfer) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                ServicePointsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
                $glueRequestTransfer->getLocale(),
            );
        }

        $servicePointUuid = $this->getParentGlueResourceTransfer($glueRequestTransfer)->getIdOrFail();
        $servicePointTransfer = (new ServicePointTransfer())->setUuid($servicePointUuid);
        $servicePointAddressTransfer = $this->servicePointAddressMapper->mapApiServicePointAddressesAttributesTransferToServicePointAddressTransfer(
            $apiServicePointAddressesAttributesTransfer,
            (new ServicePointAddressTransfer())->setServicePoint($servicePointTransfer),
        );

        $servicePointAddressCollectionRequestTransfer = $this->createServicePointAddressCollectionRequestTransfer($servicePointAddressTransfer);
        $servicePointAddressCollectionResponseTransfer = $this->servicePointFacade->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);

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
