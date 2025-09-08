<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\SearchHttp\SearchHttpConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpRepository getRepository()
 * @method \Spryker\Zed\SearchHttp\Communication\SearchHttpCommunicationFactory getFactory()
 * @method \Spryker\Zed\SearchHttp\Business\SearchHttpFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchHttp\SearchHttpConfig getConfig()
 */
class SearchHttpSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return SearchHttpConfig::SEARCH_HTTP_CONFIG_RESOURCE_NAME;
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
     * @return array<string, mixed>
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
        return SearchHttpConfig::SEARCH_HTTP_CONFIG_SYNC_QUEUE;
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
        return $this->getFactory()->getConfig()->getSearchHttpSynchronizationPoolName();
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
        $searchHttpConfigEntities = $this->getRepository()
            ->getFilteredSearchHttpEntityTransfers(
                (new SearchHttpConfigCriteriaTransfer())
                    ->setFilter($this->createFilterTransfer($offset, $limit))
                    ->setIds($ids),
            );

        return $this->mapSearchHttpConfigTransfersToSynchronizationDataTransfers($searchHttpConfigEntities);
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
     * @param \Propel\Runtime\Collection\ObjectCollection $searchHttpConfigEntities
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    protected function mapSearchHttpConfigTransfersToSynchronizationDataTransfers(ObjectCollection $searchHttpConfigEntities): array
    {
        $synchronizationDataTransfers = [];

        foreach ($searchHttpConfigEntities as $searchHttpConfigEntity) {
            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->fromArray($searchHttpConfigEntity->toArray(), true);
        }

        return $synchronizationDataTransfers;
    }
}
