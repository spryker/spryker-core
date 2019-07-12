<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Synchronization;

use Spryker\Shared\ProductAlternativeStorage\ProductAlternativeStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig getConfig()
 */
class ProductReplacementForSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
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
        return ProductAlternativeStorageConfig::PRODUCT_REPLACEMENT_RESOURCE_NAME;
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
        return ProductAlternativeStorageConfig::PRODUCT_ALTERNATIVE_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getProductReplacementForSynchronizationPoolName();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(array $ids = []): array
    {
        $productReplacementForStorageEntities = $this->findProductReplacementForStorageEntities($ids);

        return $this->getFactory()
            ->createProductReplacementForStorageMapper()
            ->mapProductReplacementForStorageEntitiesToSynchronizationDataTransfers($productReplacementForStorageEntities);
    }

    /**
     * @param int[] $ids
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage[]
     */
    protected function findProductReplacementForStorageEntities(array $ids): array
    {
        if ($ids === []) {
            return $this->getRepository()->findAllProductReplacementForStorageEntities();
        }

        return $this->getRepository()->findProductReplacementForStorageEntitiesByIds($ids);
    }
}
