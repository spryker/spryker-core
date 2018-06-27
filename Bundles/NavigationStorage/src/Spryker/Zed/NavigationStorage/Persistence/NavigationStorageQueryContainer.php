<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Persistence;

use Orm\Zed\Navigation\Persistence\Map\SpyNavigationNodeTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\NavigationStorage\Persistence\NavigationStoragePersistenceFactory getFactory()
 */
class NavigationStorageQueryContainer extends AbstractQueryContainer implements NavigationStorageQueryContainerInterface
{
    const FK_NAVIGATION = 'fkNavigation';

    /**
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
     * @api
     *
     * @param array $navigationNodeIds
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
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
     * @api
     *
     * @param array $urlIds
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
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
     * @api
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryNavigation()
    {
        return $this->getFactory()
            ->getNavigationQueryContainer()
            ->queryNavigation();
    }
}
