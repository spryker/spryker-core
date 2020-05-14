<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStoragePersistenceFactory getFactory()
 */
class ConfigurableBundleStorageRepository extends AbstractRepository implements ConfigurableBundleStorageRepositoryInterface
{
    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage[]
     */
    public function getConfigurableBundleTemplateStorageEntityMap(array $configurableBundleTemplateIds): array
    {
        $configurableBundleTemplateStorageEntities = $this->getFactory()
            ->getConfigurableBundleTemplateStoragePropelQuery()
            ->filterByFkConfigurableBundleTemplate_In($configurableBundleTemplateIds)
            ->find();

        $configurableBundleTemplateStorageEntityMap = [];

        foreach ($configurableBundleTemplateStorageEntities as $configurableBundleTemplateStorageEntity) {
            $configurableBundleTemplateStorageEntityMap[$configurableBundleTemplateStorageEntity->getFkConfigurableBundleTemplate()]
                = $configurableBundleTemplateStorageEntity;
        }

        return $configurableBundleTemplateStorageEntityMap;
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage[][]
     */
    public function getConfigurableBundleTemplateImageStorageEntityMap(array $configurableBundleTemplateIds): array
    {
        $configurableBundleTemplateImageStorageEntities = $this->getFactory()
            ->getConfigurableBundleTemplateImageStoragePropelQuery()
            ->filterByFkConfigurableBundleTemplate_In($configurableBundleTemplateIds)
            ->find();

        $localizedConfigurableBundleTemplateImageStorageEntityMap = [];

        foreach ($configurableBundleTemplateImageStorageEntities as $configurableBundleTemplateImageStorageEntity) {
            $localizedConfigurableBundleTemplateImageStorageEntityMap[$configurableBundleTemplateImageStorageEntity->getFkConfigurableBundleTemplate()][$configurableBundleTemplateImageStorageEntity->getLocale()] = $configurableBundleTemplateImageStorageEntity;
        }

        return $localizedConfigurableBundleTemplateImageStorageEntityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ConfigurableBundleStorage\Persistence\Base\SpyConfigurableBundleTemplateStorage[]
     */
    public function getFilteredConfigurableBundleTemplateStorageEntities(
        FilterTransfer $filterTransfer,
        array $configurableBundleTemplateIds
    ): ObjectCollection {
        $configurableBundleTemplateStoragePropelQuery = $this->getFactory()
            ->getConfigurableBundleTemplateStoragePropelQuery();

        if ($configurableBundleTemplateIds) {
            $configurableBundleTemplateStoragePropelQuery->filterByFkConfigurableBundleTemplate_In($configurableBundleTemplateIds);
        }

        $configurableBundleTemplateStoragePropelQuery = $this->buildQueryFromCriteria(
            $configurableBundleTemplateStoragePropelQuery,
            $filterTransfer
        )->setFormatter(ObjectFormatter::class);

        return $configurableBundleTemplateStoragePropelQuery->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Generated\Shared\Transfer\SpyConfigurableBundleTemplateImageStorageEntityTransfer[]
     */
    public function getFilteredConfigurableBundleTemplateImageStorageEntities(FilterTransfer $filterTransfer, array $configurableBundleTemplateIds): array
    {
        $configurableBundleTemplateImageStoragePropelQuery = $this->getFactory()
            ->getConfigurableBundleTemplateImageStoragePropelQuery();

        if ($configurableBundleTemplateIds) {
            $configurableBundleTemplateImageStoragePropelQuery->filterByFkConfigurableBundleTemplate_In($configurableBundleTemplateIds);
        }

        return $this->buildQueryFromCriteria($configurableBundleTemplateImageStoragePropelQuery, $filterTransfer)
            ->find();
    }
}
