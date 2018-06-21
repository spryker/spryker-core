<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Communication\Plugin\Synchronization;

use Spryker\Shared\ProductGroupStorage\ProductGroupStorageConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;

/**
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductGroupStorage\Communication\ProductGroupStorageCommunicationFactory getFactory()
 */
class ProductGroupSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataQueryContainerPluginInterface
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
        return ProductGroupStorageConstants::PRODUCT_GROUP_RESOURCE_NAME;
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
        $query = $this->getQueryContainer()->queryProductAbstractGroupStorageByIds($ids);

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
        return ProductGroupStorageConstants::PRODUCT_GROUP_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getProductGroupSynchronizationPoolName();
    }
}
