<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\Creator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade\ShipmentTypesBackendApiToShipmentTypeFacadeInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder\ShipmentTypeResponseBuilderInterface;

class ShipmentTypeCreator implements ShipmentTypeCreatorInterface
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
    public function createShipmentType(
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $shipmentTypeTransfer = $this->shipmentTypeMapper->mapShipmentTypesBackendApiAttributesTransferToShipmentTypeTransfer(
            $shipmentTypesBackendApiAttributesTransfer,
            new ShipmentTypeTransfer(),
        );

        $shipmentTypeCollectionRequestTransfer = $this->createShipmentTypeCollectionRequestTransfer($shipmentTypeTransfer);
        $shipmentTypeCollectionResponseTransfer = $this->shipmentTypeFacade->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

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
}
