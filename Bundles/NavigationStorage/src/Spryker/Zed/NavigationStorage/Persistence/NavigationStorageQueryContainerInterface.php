<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Persistence;

use Orm\Zed\Navigation\Persistence\SpyNavigationQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface NavigationStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $localeNames
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocalesWithLocaleNames(array $localeNames);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $navigationIds
     *
     * @return \Orm\Zed\NavigationStorage\Persistence\SpyNavigationStorageQuery
     */
    public function queryNavigationStorageByNavigationIds(array $navigationIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $navigationNodeIds
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryNavigationIdsByNavigationNodeIds(array $navigationNodeIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $urlIds
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryNavigationIdsByUrlIds(array $urlIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $navigationIds
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation(array $navigationIds): SpyNavigationQuery;
}
