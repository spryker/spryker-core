<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence;

use Orm\Zed\Navigation\Persistence\Map\SpyNavigationNodeTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Navigation\Persistence\NavigationPersistenceFactory getFactory()
 */
class NavigationEntityManager extends AbstractEntityManager implements NavigationEntityManagerInterface
{
    /**
     * @param int[] $duplicatedNavigationNodeIdsByNavigationNodeIds [navigationNodeId => duplicatedNavigationNodeId]
     *
     * @return void
     */
    public function updateFkParentNavigationNodeForDuplicatedNavigationNodes(array $duplicatedNavigationNodeIdsByNavigationNodeIds): void
    {
        $entities = $this->getFactory()
            ->createNavigationNodeQuery()
                ->filterByIdNavigationNode_In(array_values($duplicatedNavigationNodeIdsByNavigationNodeIds))
            ->where(SpyNavigationNodeTableMap::COL_FK_PARENT_NAVIGATION_NODE . Criteria::ISNOTNULL)
            ->find();

        foreach ($entities as $entity) {
            $entity
                ->setFkParentNavigationNode($duplicatedNavigationNodeIdsByNavigationNodeIds[$entity->getFkParentNavigationNode()])
                ->save();
        }
    }
}
