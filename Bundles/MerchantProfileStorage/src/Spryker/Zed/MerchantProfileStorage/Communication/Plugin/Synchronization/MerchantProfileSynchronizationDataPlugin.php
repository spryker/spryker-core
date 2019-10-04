<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Shared\MerchantProfileStorage\MerchantProfileStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfileStorage\Communication\MerchantProfileStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface getRepository()()
 */
class MerchantProfileSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
{
    /**
     * @uses \Propel\Runtime\ActiveQuery\Criteria::ASC
     */
    protected const ORDER_DIRECTION = 'ASC';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return MerchantProfileStorageConfig::MERCHANT_PROFILE_RESOURCE_NAME;
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
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit);
        $merchantProfileStorageTransfers = $this->getRepository()
            ->getFilteredMerchantProfileStorageTransfers($filterTransfer, $ids);

        return $this->mapMerchantProfileStorageTransfersToSynchronizationDataTransfers($merchantProfileStorageTransfers);
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
        return MerchantProfileStorageConfig::MERCHANT_PROFILE_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getMerchantProfileSynchronizationPoolName();
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOffset($offset)
            ->setLimit($limit);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileStorageTransfer[] $merchantProfileStorageTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    protected function mapMerchantProfileStorageTransfersToSynchronizationDataTransfers(array $merchantProfileStorageTransfers): array
    {
        $synchronizationDataTransfers = [];
        foreach ($merchantProfileStorageTransfers as $merchantProfileStorageTransfer) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            /** @var string $data */
            $data = $merchantProfileStorageTransfer->getData();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($merchantProfileStorageTransfer->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }
}
