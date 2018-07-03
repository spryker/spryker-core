<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Event;

use Orm\Zed\ProductMeasurementUnit\Persistence\Map\SpyProductMeasurementUnitTableMap;
use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceRepositoryPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductMeasurementUnit\Dependency\ProductMeasurementUnitEvents;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Business\ProductMeasurementUnitStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Communication\ProductMeasurementUnitStorageCommunicationFactory getFactory()
 */
class ProductMeasurementUnitEventResourcePlugin extends AbstractPlugin implements EventResourceRepositoryPluginInterface
{
    /**
     * Specification:
     *  - Returns the name of resource
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductMeasurementUnitStorageConfig::PRODUCT_MEASUREMENT_UNIT_RESOURCE_NAME;
    }

    /**
     * Specification:
     *  - Returns query of resource entity, provided $ids parameter
     *    will apply to query to limit the result
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function getData($ids = []): array
    {
        if (!empty($ids)) {
            return $this->getFacade()->findAllProductMeasurementUnitTransfers();
        }
        return $this->getFacade()->findProductMeasurementUnitTransfers($ids);
    }

    /**
     * Specification:
     *  - Returns the event name of resource entity
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return ProductMeasurementUnitEvents::PRODUCT_MEASUREMENT_UNIT_PUBLISH;
    }

    /**
     * Specification:
     *  - Returns the name of ID column for publishing
     *
     * @api
     *
     * @return string
     */
    public function getIdColumnName(): string
    {
        return SpyProductMeasurementUnitTableMap::COL_ID_PRODUCT_MEASUREMENT_UNIT;
    }
}
