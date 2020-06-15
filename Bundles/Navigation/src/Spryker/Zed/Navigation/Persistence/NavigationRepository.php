<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence;

use Generated\Shared\Transfer\NavigationCriteriaTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Navigation\Persistence\NavigationPersistenceFactory getFactory()
 */
class NavigationRepository extends AbstractRepository implements NavigationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationCriteriaTransfer $navigationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer|null
     */
    public function findNavigationByCriteria(NavigationCriteriaTransfer $navigationCriteriaTransfer): ?NavigationTransfer
    {
        $navigationQuery = $this->getFactory()->createNavigationQuery();
        $navigationQuery = $this->addFilteringByCriteria($navigationCriteriaTransfer, $navigationQuery);

        $navigationEntity = $navigationQuery->findOne();

        if ($navigationEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createNavigationMapper()
            ->mapNavigationEntityToNavigationTransfer($navigationEntity, new NavigationTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\NavigationTransfer[]
     */
    public function getAllNavigations(): array
    {
        $navigationEntities = $this->getFactory()
            ->createNavigationQuery()
            ->find();

        if ($navigationEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createNavigationMapper()
            ->mapNavigationEntitiesToNavigationTransfers($navigationEntities, []);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function checkNavigationWithKeyExists(string $key): bool
    {
        return $this->getFactory()
            ->createNavigationQuery()
            ->filterByKey($key)
            ->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationCriteriaTransfer $navigationCriteriaTransfer
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationQuery $navigationQuery
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    protected function addFilteringByCriteria(NavigationCriteriaTransfer $navigationCriteriaTransfer, SpyNavigationQuery $navigationQuery): SpyNavigationQuery
    {
        $idNavigation = $navigationCriteriaTransfer->getIdNavigation();
        $navigationKey = $navigationCriteriaTransfer->getKey();

        if ($idNavigation) {
            $navigationQuery->filterByIdNavigation($idNavigation);
        }

        if ($navigationKey) {
            $navigationQuery->filterByKey($navigationKey);
        }

        return $navigationQuery;
    }
}
