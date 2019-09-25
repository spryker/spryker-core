<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @deprecated Use \Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Synchronization\ProductMeasurementUnitSynchronizationDataBulkPlugin instead.
 *
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Business\ProductMeasurementUnitStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Communication\ProductMeasurementUnitStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig getConfig()
 */
class ProductMeasurementUnitSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    public function getData(array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $productMeasurementUnitTransfers = $this->getRepository()->findProductMeasurementUnitStorageEntities($ids);

        if ($ids === []) {
            $productMeasurementUnitTransfers = $this->getRepository()->findAllProductMeasurementUnitStorageEntities();
        }

        foreach ($productMeasurementUnitTransfers as $productMeasurementUnitTransfer) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            $synchronizationDataTransfer->setData($productMeasurementUnitTransfer->getData());
            $synchronizationDataTransfer->setKey($productMeasurementUnitTransfer->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return ProductMeasurementUnitStorageConfig::PRODUCT_MEASUREMENT_UNIT_SYNC_STORAGE_QUEUE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName(): ?string
    {
        return $this->getFactory()->getConfig()->getProductMeasurementUnitSynchronizationPoolName();
    }
}
