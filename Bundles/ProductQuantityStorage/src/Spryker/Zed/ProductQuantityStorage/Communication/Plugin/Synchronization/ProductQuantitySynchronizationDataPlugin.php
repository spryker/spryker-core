<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Communication\Plugin\Synchronization;

use Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorage;
use Spryker\Shared\ProductQuantityStorage\ProductQuantityStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageRepositoryInterface getRepository()()
 * @method \Spryker\Zed\ProductQuantityStorage\Business\ProductQuantityStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductQuantityStorage\Communication\ProductQuantityStorageCommunicationFactory getFactory()
 */
class ProductQuantitySynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
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
        return ProductQuantityStorageConfig::PRODUCT_QUANTITY_RESOURCE_NAME;
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
        return false;
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
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria[]
     */
    public function getData($ids = [])
    {
        $productQuantityEntities = [];
        $productQuantityTransfers = $this->getRepository()->findProductQuantityStorageEntitiesByProductIds($ids);

        if (empty($ids)) {
            $productQuantityTransfers = $this->getRepository()->findAllProductQuantityStorageEntities();
        }

        foreach ($productQuantityTransfers as $productQuantityTransfer) {
            $productQuantityEntity = new SpyProductQuantityStorage();
            $productQuantityEntity->fromArray($productQuantityTransfer->toArray());
            $productQuantityEntities[] = $productQuantityEntity;
        }

        return $productQuantityEntities;
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
        return ProductQuantityStorageConfig::PRODUCT_QUANTITY_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getProductQuantitySynchronizationPoolName();
    }
}
