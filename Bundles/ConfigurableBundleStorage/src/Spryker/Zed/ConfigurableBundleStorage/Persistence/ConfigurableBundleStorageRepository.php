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
    public function getIndexedConfigurableBundleTemplateEntities(array $configurableBundleTemplateIds): array
    {
        $configurableBundleTemplateEntities = $this->getFactory()
            ->getConfigurableBundleTemplatePropelQuery()
            ->leftJoinWithSpyConfigurableBundleTemplateSlot()
            ->filterByIdConfigurableBundleTemplate_In($configurableBundleTemplateIds)
            ->find();

        $indexedConfigurableBundleTemplateEntities = [];

        foreach ($configurableBundleTemplateEntities as $configurableBundleTemplateEntity) {
            $indexedConfigurableBundleTemplateEntities[$configurableBundleTemplateEntity->getIdConfigurableBundleTemplate()]
                = $configurableBundleTemplateEntity;
        }

        return $indexedConfigurableBundleTemplateEntities;
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage[]
     */
    public function getIndexedConfigurableBundleTemplateStorageEntities(array $configurableBundleTemplateIds): array
    {
        $configurableBundleTemplateStorageEntities = $this->getFactory()
            ->getConfigurableBundleTemplateStoragePropelQuery()
            ->filterByFkConfigurableBundleTemplate_In($configurableBundleTemplateIds)
            ->find();

        $indexedConfigurableBundleTemplateStorageEntities = [];

        foreach ($configurableBundleTemplateStorageEntities as $configurableBundleTemplateStorageEntity) {
            $indexedConfigurableBundleTemplateStorageEntities[$configurableBundleTemplateStorageEntity->getFkConfigurableBundleTemplate()]
                = $configurableBundleTemplateStorageEntity;
        }

        return $indexedConfigurableBundleTemplateStorageEntities;
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
    public function getConfigurableBundlesTemplatesByFilter(FilterTransfer $filterTransfer): array
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
