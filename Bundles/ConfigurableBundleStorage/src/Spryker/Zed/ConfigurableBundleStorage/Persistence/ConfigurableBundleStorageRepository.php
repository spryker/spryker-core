<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateTableMap;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStoragePersistenceFactory getFactory()
 */
class ConfigurableBundleStorageRepository extends AbstractRepository implements ConfigurableBundleStorageRepositoryInterface
{
    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate[]
     */
    public function getConfigurableBundleTemplateEntityMap(array $configurableBundleTemplateIds): array
    {
        $configurableBundleTemplateEntities = $this->getFactory()
            ->getConfigurableBundleTemplatePropelQuery()
            ->leftJoinWithSpyConfigurableBundleTemplateSlot()
            ->filterByIdConfigurableBundleTemplate_In($configurableBundleTemplateIds)
            ->find();

        $configurableBundleTemplateEntityMap = [];

        foreach ($configurableBundleTemplateEntities as $configurableBundleTemplateEntity) {
            $configurableBundleTemplateEntityMap[$configurableBundleTemplateEntity->getIdConfigurableBundleTemplate()]
                = $configurableBundleTemplateEntity;
        }

        return $configurableBundleTemplateEntityMap;
    }

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
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Generated\Shared\Transfer\SpyConfigurableBundleTemplateStorageEntityTransfer[]
     */
    public function getFilteredConfigurableBundleTemplateStorageEntities(FilterTransfer $filterTransfer, array $configurableBundleTemplateIds): array
    {
        $configurableBundleTemplateStoragePropelQuery = $this->getFactory()
            ->getConfigurableBundleTemplateStoragePropelQuery();

        if ($configurableBundleTemplateIds) {
            $configurableBundleTemplateStoragePropelQuery->filterByFkConfigurableBundleTemplate_In($configurableBundleTemplateIds);
        }

        return $this->buildQueryFromCriteria($configurableBundleTemplateStoragePropelQuery, $filterTransfer)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer[]
     */
    public function getConfigurableBundleTemplatesByFilter(FilterTransfer $filterTransfer): array
    {
        $configurableBundleTemplatePropelQuery = $this->getFactory()
            ->getConfigurableBundleTemplatePropelQuery()
            ->select([SpyConfigurableBundleTemplateTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE]);

        $configurableBundleTemplateIds = $this->buildQueryFromCriteria($configurableBundleTemplatePropelQuery, $filterTransfer)
            ->setFormatter(SimpleArrayFormatter::class)
            ->find()
            ->toArray();

        $configurableBundleTemplateTransfers = [];

        foreach ($configurableBundleTemplateIds as $configurableBundleTemplateId) {
            $configurableBundleTemplateTransfers[] = (new ConfigurableBundleTemplateTransfer())
                ->setIdConfigurableBundleTemplate($configurableBundleTemplateId);
        }

        return $configurableBundleTemplateTransfers;
    }
}
