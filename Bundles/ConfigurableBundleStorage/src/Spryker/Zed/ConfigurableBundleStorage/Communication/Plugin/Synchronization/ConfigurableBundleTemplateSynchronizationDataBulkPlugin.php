<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\ConfigurableBundleStorage\Persistence\Map\SpyConfigurableBundleTemplateStorageTableMap;
use Spryker\Shared\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Business\ConfigurableBundleStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Communication\ConfigurableBundleStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig getConfig()
 */
class ConfigurableBundleTemplateSynchronizationDataBulkPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME;
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
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        return $this->getRepository()->getFilteredConfigurableBundleTemplateStorageDataTransfers(
            $this->createFilterTransfer($offset, $limit),
            $ids
        );
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
        return ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getConfigurableBundleTemplateSynchronizationPoolName();
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
            ->setOrderBy(SpyConfigurableBundleTemplateStorageTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_STORAGE)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
