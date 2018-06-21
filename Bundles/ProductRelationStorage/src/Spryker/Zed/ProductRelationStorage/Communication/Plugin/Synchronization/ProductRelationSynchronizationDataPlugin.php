<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Communication\Plugin\Synchronization;

use Spryker\Shared\ProductRelationStorage\ProductRelationStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductRelationStorage\Communication\ProductRelationStorageCommunicationFactory getFactory()
 */
class ProductRelationSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataQueryContainerPluginInterface
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
        return ProductRelationStorageConfig::PRODUCT_ABSTRACT_RELATION_RESOURCE_NAME;
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
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryData($ids = [])
    {
        $query = $this->getQueryContainer()->queryProductAbstractRelationStorageByIds($ids);

        if (empty($ids)) {
            $query->clear();
        }

        return $query;
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
        return ProductRelationStorageConfig::PRODUCT_ABSTRACT_RELATION_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getProductAbstractRelationSynchronizationPoolName();
    }
}
