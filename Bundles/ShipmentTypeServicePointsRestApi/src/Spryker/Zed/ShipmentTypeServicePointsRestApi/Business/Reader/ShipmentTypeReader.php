<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig
     */
    protected ShipmentTypeServicePointsRestApiConfig $shipmentTypeServicePointsRestApiConfig;

    /**
     * @var \Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToShipmentFacadeInterface
     */
    protected ShipmentTypeServicePointsRestApiToShipmentFacadeInterface $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig $shipmentTypeServicePointsRestApiConfig
     * @param \Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(
        ShipmentTypeServicePointsRestApiConfig $shipmentTypeServicePointsRestApiConfig,
        ShipmentTypeServicePointsRestApiToShipmentFacadeInterface $shipmentFacade
    ) {
        $this->shipmentTypeServicePointsRestApiConfig = $shipmentTypeServicePointsRestApiConfig;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function getApplicableShipmentTypeTransfersIndexedByIdShipmentMethod(QuoteTransfer $quoteTransfer): array
    {
        $shipmentMethodsCollectionTransfer = $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);
        $shipmentTypeTransfersIndexedByIdShipmentMethod = $this->getShipmentTypeTransfersIndexedByIdShipmentMethod($shipmentMethodsCollectionTransfer);
        if ($shipmentTypeTransfersIndexedByIdShipmentMethod === []) {
            return [];
        }

        return $this->filterOutNotApplicableShipmentTypeTransfers(
            $shipmentTypeTransfersIndexedByIdShipmentMethod,
            $this->shipmentTypeServicePointsRestApiConfig->getApplicableShipmentTypeKeysForShippingAddress(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function getShipmentTypeTransfersIndexedByIdShipmentMethod(ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer): array
    {
        $shipmentTypeTransfersIndexedByIdShipmentMethod = [];
        foreach ($shipmentMethodsCollectionTransfer->getShipmentMethods() as $shipmentMethodsTransfer) {
            foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
                $idShipmentMethod = $shipmentMethodTransfer->getIdShipmentMethodOrFail();
                $shipmentTypeTransfersIndexedByIdShipmentMethod[$idShipmentMethod] = $shipmentMethodTransfer->getShipmentType();
            }
        }

        return array_filter($shipmentTypeTransfersIndexedByIdShipmentMethod);
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfersIndexedByIdShipmentMethod
     * @param list<string> $applicableShipmentTypeKeys
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function filterOutNotApplicableShipmentTypeTransfers(
        array $shipmentTypeTransfersIndexedByIdShipmentMethod,
        array $applicableShipmentTypeKeys
    ): array {
        $filteredShipmentTypeTransfersIndexedByIdShipmentMethod = [];
        foreach ($shipmentTypeTransfersIndexedByIdShipmentMethod as $idShipmentMethod => $shipmentTypeTransfer) {
            if (in_array($shipmentTypeTransfer->getKeyOrFail(), $applicableShipmentTypeKeys, true)) {
                $filteredShipmentTypeTransfersIndexedByIdShipmentMethod[$idShipmentMethod] = $shipmentTypeTransfer;
            }
        }

        return $filteredShipmentTypeTransfersIndexedByIdShipmentMethod;
    }
}
