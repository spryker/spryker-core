<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Synchronization;

use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage;
use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface getRepository()()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Business\ProductMeasurementUnitStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Communication\ProductMeasurementUnitStorageCommunicationFactory getFactory()
 */
class ProductConcreteMeasurementUnitSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
{
    /**
     * Specification:
     *  - Returns the resource name of the storage or search module
     *
     * @api
     *
     * @return string
     */
    public function getResourceName()
    {
        return ProductMeasurementUnitStorageConfig::PRODUCT_CONCRETE_MEASUREMENT_UNIT_RESOURCE_NAME;
    }

    /**
     * Specification:
     *  - Returns true if this entity has multi-store concept
     *
     * @api
     *
     * @return bool
     */
    public function hasStore()
    {
        return true;
    }

    /**
     * Specification:
     *  - Returns array of storage or search synchronized data, provided $ids parameter
     *    will limit the result
     *
     * @api
     *
     * @param array $ids
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    public function getData($ids = [])
    {
        $productConcreteMeasurementUnitEntities = [];
        $productConcreteMeasurementUnitTransfers = $this->getRepository()->findProductConcreteMeasurementUnitStorageEntities($ids);

        if (empty($ids)) {
            $productConcreteMeasurementUnitTransfers = $this->getRepository()->findAllProductConcreteMeasurementUnitStorageEntities();
        }

        foreach ($productConcreteMeasurementUnitTransfers as $productConcreteMeasurementUnitTransfer) {
            $productConcreteMeasurementUnitEntity = new SpyProductConcreteMeasurementUnitStorage();
            $productConcreteMeasurementUnitEntity->fromArray($productConcreteMeasurementUnitTransfer->toArray());
            $productConcreteMeasurementUnitEntities[] = $productConcreteMeasurementUnitEntity;
        }

        return $productConcreteMeasurementUnitEntities;
    }

    /**
     * Specification:
     *  - Returns array of configuration parameter which needed for Redis or Elasticsearch
     *
     * @api
     *
     * @return array
     */
    public function getParams()
    {
        return [];
    }

    /**
     * Specification:
     *  - Returns synchronization queue name
     *
     * @api
     *
     * @return string
     */
    public function getQueueName()
    {
        return ProductMeasurementUnitStorageConfig::PRODUCT_MEASUREMENT_UNIT_SYNC_STORAGE_QUEUE;
    }

    /**
     * Specification:
     *  - Returns synchronization queue pool name for broadcasting messages
     *
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName()
    {
        return $this->getFactory()->getConfig()->getProductConcreteMeasurementUnitSynchronizationPoolName();
    }
}
