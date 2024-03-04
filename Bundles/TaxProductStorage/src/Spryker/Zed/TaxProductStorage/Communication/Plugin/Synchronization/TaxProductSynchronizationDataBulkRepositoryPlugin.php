<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Shared\TaxProductStorage\TaxProductStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\TaxProductStorage\TaxProductStorageConfig getConfig()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxProductStorage\Business\TaxProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxProductStorage\Communication\TaxProductStorageCommunicationFactory getFactory()
 */
class TaxProductSynchronizationDataBulkRepositoryPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
{
    /**
     * @uses \Orm\Zed\TaxProductStorage\Persistence\Map\SpyTaxProductStorageTableMap::COL_ID_TAX_PRODUCT_STORAGE
     *
     * @var string
     */
    protected const COL_ID_TAX_PRODUCT_STORAGE = 'spy_tax_product_storage.id_tax_product_storage';

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function hasStore(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit);

        return $this->getRepository()->getSynchronizationDataTransfersFromTaxProductStorages($ids, $filterTransfer);
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
        return TaxProductStorageConfig::PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_QUEUE;
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
        return $this->getConfig()->getTaxProductSynchronizationPoolName();
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
            ->setOrderBy(static::COL_ID_TAX_PRODUCT_STORAGE)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
