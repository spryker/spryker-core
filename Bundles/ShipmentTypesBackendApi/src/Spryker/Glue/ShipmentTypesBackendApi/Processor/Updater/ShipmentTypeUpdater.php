<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\Updater;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade\ShipmentTypesBackendApiToShipmentTypeFacadeInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder\ShipmentTypeResponseBuilderInterface;
use Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig;

class ShipmentTypeUpdater implements ShipmentTypeUpdaterInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface
     */
    protected ShipmentTypeMapperInterface $shipmentTypeMapper;

    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder\ShipmentTypeResponseBuilderInterface
     */
    protected ShipmentTypeResponseBuilderInterface $shipmentTypeResponseBuilder;

    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade\ShipmentTypesBackendApiToShipmentTypeFacadeInterface
     */
    protected ShipmentTypesBackendApiToShipmentTypeFacadeInterface $shipmentTypeFacade;

    /**
     * @param \Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface $shipmentTypeMapper
     * @param \Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder\ShipmentTypeResponseBuilderInterface $shipmentTypeResponseBuilder
     * @param \Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade\ShipmentTypesBackendApiToShipmentTypeFacadeInterface $shipmentTypeFacade
     */
    public function __construct(
        ShipmentTypeMapperInterface $shipmentTypeMapper,
        ShipmentTypeResponseBuilderInterface $shipmentTypeResponseBuilder,
        ShipmentTypesBackendApiToShipmentTypeFacadeInterface $shipmentTypeFacade
    ) {
        $this->shipmentTypeMapper = $shipmentTypeMapper;
        $this->shipmentTypeResponseBuilder = $shipmentTypeResponseBuilder;
        $this->shipmentTypeFacade = $shipmentTypeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateShipmentType(
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $shipmentTypeTransfer = $this->findShipmentType($glueRequestTransfer->getResourceOrFail()->getIdOrFail());
        if ($shipmentTypeTransfer === null) {
            $errorTransfers = new ArrayObject();
            $errorTransfers->append($this->createEntityNotFoundErrorTransfer());

            return $this->shipmentTypeResponseBuilder->createErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        $shipmentTypeTransfer = $this->shipmentTypeMapper->mapShipmentTypesBackendApiAttributesTransferToShipmentTypeTransfer(
            $shipmentTypesBackendApiAttributesTransfer,
            $shipmentTypeTransfer,
        );

        $shipmentTypeCollectionRequestTransfer = $this->createShipmentTypeCollectionRequestTransfer($shipmentTypeTransfer);
        $shipmentTypeCollectionResponseTransfer = $this->shipmentTypeFacade->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        $errorTransfers = $shipmentTypeCollectionResponseTransfer->getErrors();
        if ($errorTransfers->count() !== 0) {
            return $this->shipmentTypeResponseBuilder->createErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->shipmentTypeResponseBuilder->createShipmentTypeResponse(
            $shipmentTypeCollectionResponseTransfer->getShipmentTypes(),
        );
    }

    /**
     * @param string $shipmentTypeUuid
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer|null
     */
    protected function findShipmentType(string $shipmentTypeUuid): ?ShipmentTypeTransfer
    {
        $shipmentTypeCriteriaTransfer = $this->createShipmentTypeCriteriaTransfer($shipmentTypeUuid);
        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        return $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current();
    }

    /**
     * @param string $shipmentTypeUuid
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer
     */
    protected function createShipmentTypeCriteriaTransfer(string $shipmentTypeUuid): ShipmentTypeCriteriaTransfer
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->addUuid($shipmentTypeUuid)
            ->setWithStoreRelations(true);

        return (new ShipmentTypeCriteriaTransfer())->setShipmentTypeConditions($shipmentTypeConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer
     */
    protected function createShipmentTypeCollectionRequestTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): ShipmentTypeCollectionRequestTransfer {
        return (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);
    }

    /**
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createEntityNotFoundErrorTransfer(): ErrorTransfer
    {
        return (new ErrorTransfer())->setMessage(ShipmentTypesBackendApiConfig::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND);
    }
}
