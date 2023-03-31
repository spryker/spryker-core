<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Spryker\Shared\StoreStorage\StoreStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\StoreStorage\StoreStorageConfig getConfig()
 * @method \Spryker\Zed\StoreStorage\Business\StoreStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreStorage\Communication\StoreStorageCommunicationFactory getFactory()
 */
class StorePublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\Store\Persistence\Map\SpyStoreTableMap::COL_ID_STORE
     *
     * @var string
     */
    protected const COL_ID_STORE = 'spy_store.id_store';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $storeCriteriaTransfer = (new StoreCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())
                    ->setLimit($limit)
                    ->setOffset($offset),
            );

        return $this->getFactory()
            ->getStoreFacade()
            ->getStoreCollection($storeCriteriaTransfer)
            ->getStores()
            ->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return StoreStorageConfig::STORE_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return StoreStorageConfig::STORE_PUBLISH_WRITE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_STORE;
    }
}
