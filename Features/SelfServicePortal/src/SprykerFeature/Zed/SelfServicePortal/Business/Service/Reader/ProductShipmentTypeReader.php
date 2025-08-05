<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader;

use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Grouper\ShipmentTypeGrouperInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductShipmentTypeReader implements ProductShipmentTypeReaderInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
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
        $productShipmentTypeIds = $this->selfServicePortalRepository->getShipmentTypeIdsGroupedByIdProductConcrete($productConcreteIds);
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
