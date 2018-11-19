<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageCommunicationFactory getFactory()
 */
class ProductAbstractPackagingSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataQueryContainerPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductPackagingUnitStorageConfig::PRODUCT_ABSTRACT_PACKAGING_RESOURCE_NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|null
     */
    public function queryData($ids = []): ?ModelCriteria
    {
        $query = $this->getQueryContainer()->queryAvailabilityStorageByAvailabilityAbstractIds($ids);

        if (empty($ids)) {
            $query->clear();
        }

        return $query;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function hasStore(): bool
    {
        return false;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return ProductPackagingUnitStorageConfig::PRODUCT_PACKAGING_UNIT_SYNC_STORAGE_QUEUE;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName(): ?string
    {
        return $this->getConfig()->getProductAbstractPackagingSynchronizationPoolName();
    }
}
