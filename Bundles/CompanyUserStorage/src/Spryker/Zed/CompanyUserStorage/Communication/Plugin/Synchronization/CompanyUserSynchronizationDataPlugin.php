<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Shared\CompanyUserStorage\CompanyUserStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\CompanyUserStorage\CompanyUserStorageConfig getConfig()
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUserStorage\Business\CompanyUserStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUserStorage\Communication\CompanyUserStorageCommunicationFactory getFactory()
 */
class CompanyUserSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
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
        return CompanyUserStorageConfig::COMPANY_USER_RESOURCE_NAME;
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
     * @param array $ids
     *
     * @return array
     */
    public function getData(array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $companyUserStorageEntities = $this->findCompanyUserStorageEntities($ids);

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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return CompanyUserStorageConfig::COMPANY_USER_SYNC_STORAGE_QUEUE;
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
            ->getCompanyUserSynchronizationPoolName();
    }

    /**
     * @param array $ids
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    protected function findCompanyUserStorageEntities(array $ids): array
    {
        if ($ids === []) {
            return $this->getRepository()->findAllCompanyUserStorageEntities();
        }

        return $this->getRepository()->findCompanyUserStorageEntities($ids);
    }
}
