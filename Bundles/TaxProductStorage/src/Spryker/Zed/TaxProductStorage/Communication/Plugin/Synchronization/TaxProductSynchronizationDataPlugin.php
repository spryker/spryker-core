<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Shared\TaxProductStorage\TaxProductStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\TaxProductStorage\TaxProductStorageConfig getConfig()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepository getRepository()
 * @method \Spryker\Zed\TaxProductStorage\Business\TaxProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxProductStorage\Communication\TaxProductStorageCommunicationFactory getFactory()
 */
class TaxProductSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
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
        return TaxProductStorageConfig::PRODUCT_ABSTRACT_TAX_SET_RESOURCE_NAME;
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
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $companyUserStorageEntities = $this->findTaxProductStorageEntities($ids);

        foreach ($companyUserStorageEntities as $companyUserStorageEntity) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();

            /** @var string $data */
            $data = $companyUserStorageEntity->getData();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($companyUserStorageEntity->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
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
     * Specification:
     *  - Returns synchronization queue name
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return TaxProductStorageConfig::PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_QUEUE;
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
        return $this->getConfig()
            ->getTaxProductSyncronizationPoolName();
    }

    /**
     * @param int[] $ids
     *
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    protected function findTaxProductStorageEntities(array $ids): array
    {
        if (count($ids)) {
            return $this->getRepository()->findTaxProductStorageEntities($ids);
        }

        return $this->getRepository()->findAllTaxProductStorageEntities();
    }
}
