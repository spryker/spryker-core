<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Persistence;

use Orm\Zed\Navigation\Persistence\Map\SpyNavigationNodeTableMap;
use Orm\Zed\Navigation\Persistence\SpyNavigationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\NavigationStorage\Persistence\NavigationStoragePersistenceFactory getFactory()
 */
class NavigationStorageQueryContainer extends AbstractQueryContainer implements NavigationStorageQueryContainerInterface
{
    public const FK_NAVIGATION = 'fkNavigation';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $localeNames
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocalesWithLocaleNames(array $localeNames)
    {
        return $this->getFactory()
            ->getLocaleQueryContainer()
            ->queryLocales()
            ->filterByLocaleName_In($localeNames);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $navigationIds
     *
     * @return \Orm\Zed\NavigationStorage\Persistence\SpyNavigationStorageQuery
     */
    public function queryNavigationStorageByNavigationIds(array $navigationIds)
    {
        return $this->getFactory()
            ->createSpyNavigationStorageQuery()
            ->filterByFkNavigation_In($navigationIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $navigationNodeIds
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryNavigationIdsByNavigationNodeIds(array $navigationNodeIds)
    {
        return $this->getFactory()
            ->getNavigationQueryContainer()
            ->queryNavigationNode()
            ->filterByIdNavigationNode_In($navigationNodeIds)
            ->withColumn('DISTINCT ' . SpyNavigationNodeTableMap::COL_FK_NAVIGATION, static::FK_NAVIGATION)
            ->select([static::FK_NAVIGATION]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $urlIds
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryNavigationIdsByUrlIds(array $urlIds)
    {
        return $this->getFactory()
            ->getNavigationQueryContainer()
            ->queryNavigationNode()
            ->useSpyNavigationNodeLocalizedAttributesQuery()
                ->filterByFkUrl_In($urlIds)
            ->endUse()
            ->withColumn('DISTINCT ' . SpyNavigationNodeTableMap::COL_FK_NAVIGATION, static::FK_NAVIGATION)
            ->select([static::FK_NAVIGATION]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $navigationIds
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation(array $navigationIds): SpyNavigationQuery
    {
        return $this->getFactory()
            ->getNavigationQueryContainer()
            ->queryNavigation()
            ->filterByIdNavigation_In($navigationIds);
    }
}
