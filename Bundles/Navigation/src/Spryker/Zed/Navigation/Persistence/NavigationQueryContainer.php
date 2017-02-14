<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Navigation\Persistence\NavigationPersistenceFactory getFactory()
 */
class NavigationQueryContainer extends AbstractQueryContainer implements NavigationQueryContainerInterface
{

    /**
     * @api
     *
     * @param int $idNavigation
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigationById($idNavigation)
    {
        return $this->getFactory()
            ->createNavigationQuery()
            ->filterByIdNavigation($idNavigation);
    }

    /**
     * @api
     *
     * @param int $idNavigationNode
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodeById($idNavigationNode)
    {
        return $this->getFactory()
            ->createNavigationNodeQuery()
            ->filterByIdNavigationNode($idNavigationNode);
    }

    /**
     * @api
     *
     * @param int $idNavigationNodeLocalizedAttributes
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributesQuery
     */
    public function queryNavigationNodeLocalizedAttributesById($idNavigationNodeLocalizedAttributes)
    {
        return $this->getFactory()
            ->createNavigationNodeLocalizedAttributesQuery()
            ->filterByIdNavigationNodeLocalizedAttributes($idNavigationNodeLocalizedAttributes);
    }

    /**
     * @api
     *
     * @param int $idNavigation
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryRootNavigationNodesByIdNavigation($idNavigation)
    {
        return $this->getFactory()
            ->createNavigationNodeQuery()
            ->filterByFkNavigation($idNavigation)
            ->filterByFkParentNavigationNode(null, Criteria::ISNULL)
            ->orderByWeight(Criteria::DESC);
    }

    /**
     * @api
     *
     * @param int $fkParentNavigationNode
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodesByFkParentNavigationNode($fkParentNavigationNode)
    {
        return $this->getFactory()
            ->createNavigationNodeQuery()
            ->filterByFkParentNavigationNode($fkParentNavigationNode)
            ->orderByWeight(Criteria::DESC);
    }

}
