<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Business\ConfigurableBundlePageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Communication\ConfigurableBundlePageSearchCommunicationFactory getFactory()
 */
class ConfigurableBundleTemplatePageSynchronizationDataBulkPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME;
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
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $configurableBundleTemplatePageSearchCollectionTransfer = $this->getFacade()->getConfigurableBundleTemplatePageSearchCollection(
            (new ConfigurableBundleTemplatePageSearchFilterTransfer())->setConfigurableBundleTemplateIds($ids)
                ->setOffset($offset)
                ->setLimit($limit),
        );

        foreach ($configurableBundleTemplatePageSearchCollectionTransfer->getConfigurableBundleTemplatePageSearches() as $configurableBundleTemplatePageSearchTransfer) {
            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($configurableBundleTemplatePageSearchTransfer->getData())
                ->setKey($configurableBundleTemplatePageSearchTransfer->getKey())
                ->setLocale($configurableBundleTemplatePageSearchTransfer->getLocale());
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
        return ['type' => 'page'];
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
        return ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_SEARCH_QUEUE;
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
        return $this->getFactory()
            ->getConfig()
            ->getConfigurableBundlePageSynchronizationPoolName();
    }
}
