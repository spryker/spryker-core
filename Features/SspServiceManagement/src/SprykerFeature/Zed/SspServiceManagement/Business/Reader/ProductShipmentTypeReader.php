<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Reader;

use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Grouper\ShipmentTypeGrouperInterface;
use SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface;

class ProductShipmentTypeReader implements ProductShipmentTypeReaderInterface
{
    /**
     * @param \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface $sspServiceManagementRepository
     * @param \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface $shipmentTypeFacade
     * @param \SprykerFeature\Zed\SspServiceManagement\Business\Grouper\ShipmentTypeGrouperInterface $shipmentTypeGrouper
     */
    public function __construct(
        protected SspServiceManagementRepositoryInterface $sspServiceManagementRepository,
        protected ShipmentTypeFacadeInterface $shipmentTypeFacade,
        protected ShipmentTypeGrouperInterface $shipmentTypeGrouper
    ) {
    }

    /**
     * @param list<int> $productConcreteIds
     *
     * @return array<int, list<\Generated\Shared\Transfer\ShipmentTypeTransfer>>
     */
    public function getShipmentTypesGroupedByIdProductConcrete(array $productConcreteIds): array
    {
        $productShipmentTypeIds = $this->sspServiceManagementRepository->getShipmentTypeIdsGroupedByIdProductConcrete($productConcreteIds);
        if (!$productShipmentTypeIds) {
            return [];
        }

        $shipmentTypeIds = $this->extractShipmentTypeIds($productShipmentTypeIds);
        $shipmentTypeTransfers = $this->getShipmentTypeTransfers($shipmentTypeIds);

        return $this->shipmentTypeGrouper->groupShipmentTypesByIdProductConcrete($productShipmentTypeIds, $shipmentTypeTransfers);
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function getShipmentTypeTransfers(array $shipmentTypeIds): array
    {
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions(
                (new ShipmentTypeConditionsTransfer())->setShipmentTypeIds(
                    $shipmentTypeIds,
                ),
            );

        return $this->shipmentTypeFacade
            ->getShipmentTypeCollection($shipmentTypeCriteriaTransfer)
            ->getShipmentTypes()
            ->getArrayCopy();
    }

    /**
     * @param array<int, list<int>> $productShipmentTypeIds
     *
     * @return list<int>
     */
    protected function extractShipmentTypeIds(array $productShipmentTypeIds): array
    {
        return array_unique(
            array_merge(...array_values($productShipmentTypeIds)),
        );
    }
}
