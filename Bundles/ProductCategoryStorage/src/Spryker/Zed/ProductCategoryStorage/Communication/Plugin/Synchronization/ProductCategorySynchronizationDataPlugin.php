<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Synchronization;

use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery;
use Spryker\Shared\ProductCategoryStorage\ProductCategoryStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategoryStorage\Communication\ProductCategoryStorageCommunicationFactory getFactory()
 */
class ProductCategorySynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataQueryContainerPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductCategoryStorageConfig::PRODUCT_ABSTRACT_CATEGORY_RESOURCE_NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function hasStore(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery
     */
    public function queryData($ids = []): SpyProductAbstractCategoryStorageQuery
    {
        $query = $this->getQueryContainer()->queryProductAbstractCategoryStorageByIds($ids);

        if (empty($ids)) {
            $query->clear();
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return ProductCategoryStorageConfig::PRODUCT_ABSTRACT_CATEGORY_SYNC_STORAGE_QUEUE;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName(): ?string
    {
        return $this->getFactory()->getConfig()->getProductCategorySynchronizationPoolName();
    }
}
