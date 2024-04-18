<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Persistence;

use Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpPersistenceFactory getFactory()
 */
class SearchHttpEntityManager extends AbstractEntityManager implements SearchHttpEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function saveSearchHttpConfig(
        SearchHttpConfigTransfer $searchHttpConfigTransfer,
        StoreTransfer $storeTransfer
    ): void {
        $searchHttpConfigEntity = $this->findSearchHttpConfigEntityByStoreName($storeTransfer->getNameOrFail());

        if ($searchHttpConfigEntity) {
            $searchHttpConfigCollectionTransfer = $this->getFactory()
                ->createSearchHttpConfigMapper()
                ->mapSearchHttpConfigEntityToSearchHttpConfigCollection(
                    $searchHttpConfigEntity,
                    new SearchHttpConfigCollectionTransfer(),
                );

            $searchHttpConfigCollectionTransfer = $this
                ->setSearchHttpConfigToCollection(
                    $searchHttpConfigCollectionTransfer,
                    $searchHttpConfigTransfer,
                );
        } else {
            $searchHttpConfigCollectionTransfer = (new SearchHttpConfigCollectionTransfer())
                ->addSearchHttpConfig($searchHttpConfigTransfer);

            $searchHttpConfigEntity = new SpySearchHttpConfig();
        }

        $this->getFactory()
            ->createSearchHttpConfigMapper()
            ->mapSearchHttpConfigTransferCollectionToSearchHttpConfigEntity(
                $searchHttpConfigCollectionTransfer,
                $searchHttpConfigEntity,
            )
            ->setStore($storeTransfer->getNameOrFail())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $applicationId
     *
     * @return void
     */
    public function deleteSearchHttpConfig(StoreTransfer $storeTransfer, string $applicationId): void
    {
        $searchHttpConfigEntity = $this->findSearchHttpConfigEntityByStoreName($storeTransfer->getNameOrFail());

        if ($searchHttpConfigEntity) {
            $searchHttpConfigCollectionTransfer = $this->getFactory()
                ->createSearchHttpConfigMapper()
                ->mapSearchHttpConfigEntityToSearchHttpConfigCollection(
                    $searchHttpConfigEntity,
                    new SearchHttpConfigCollectionTransfer(),
                );

            $searchHttpConfigCollectionTransfer = $this
                ->removeSearchHttpConfigFromCollectionByApplicationId(
                    $searchHttpConfigCollectionTransfer,
                    $applicationId,
                );

            $this->getFactory()
                ->createSearchHttpConfigMapper()
                ->mapSearchHttpConfigTransferCollectionToSearchHttpConfigEntity(
                    $searchHttpConfigCollectionTransfer,
                    $searchHttpConfigEntity,
                )
                ->setStore($storeTransfer->getNameOrFail())
                ->save();
        }
    }

    /**
     * @param string $storeName
     *
     * @return \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig|null
     */
    protected function findSearchHttpConfigEntityByStoreName(string $storeName): ?SpySearchHttpConfig
    {
        return $this->getFactory()
            ->createSearchHttpPropelQuery()
            ->filterByStore($storeName)
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransfer
     * @param string $applicationId
     *
     * @return \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer
     */
    protected function removeSearchHttpConfigFromCollectionByApplicationId(
        SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransfer,
        string $applicationId
    ): SearchHttpConfigCollectionTransfer {
        foreach ($searchHttpConfigCollectionTransfer->getSearchHttpConfigs() as $key => $searchHttpConfigTransfer) {
            if ($searchHttpConfigTransfer->getApplicationId() === $applicationId) {
                $searchHttpConfigCollectionTransfer->getSearchHttpConfigs()->offsetUnset($key);
            }
        }

        return $searchHttpConfigCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransfer
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer
     */
    protected function setSearchHttpConfigToCollection(
        SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransfer,
        SearchHttpConfigTransfer $searchHttpConfigTransfer
    ): SearchHttpConfigCollectionTransfer {
        $this->removeSearchHttpConfigFromCollectionByApplicationId(
            $searchHttpConfigCollectionTransfer,
            $searchHttpConfigTransfer->getApplicationIdOrFail(),
        );

        return $searchHttpConfigCollectionTransfer->addSearchHttpConfig($searchHttpConfigTransfer);
    }
}
