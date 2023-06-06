<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeStorage\Business\ShipmentTypeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentTypeStorage\Communication\ShipmentTypeStorageCommunicationFactory getFactory()
 */
class ShipmentTypePublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap::COL_ID_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_ID_SHIPMENT_TYPE = 'spy_shipment_type.id_shipment_type';

    /**
     * {@inheritDoc}
     * - Retrieves shipment types by provided limit and offset.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\ServicePointTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset($offset)
            ->setLimit($limit);
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())->setPagination($paginationTransfer);

        return $this->getFactory()
            ->getShipmentTypeFacade()
            ->getShipmentTypeCollection($shipmentTypeCriteriaTransfer)
            ->getShipmentTypes()
            ->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ShipmentTypeStorageConfig::SHIPMENT_TYPE_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return ShipmentTypeStorageConfig::SHIPMENT_TYPE_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_SHIPMENT_TYPE;
    }
}
