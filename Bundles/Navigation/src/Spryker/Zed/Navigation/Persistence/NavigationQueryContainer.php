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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
            ->orderByPosition(Criteria::ASC)
            ->orderByIdNavigationNode(Criteria::ASC);
    }

    /**
     * {@inheritDoc}
     *
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
            ->orderByPosition(Criteria::ASC)
            ->orderByIdNavigationNode(Criteria::ASC);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation()
    {
        return $this->getFactory()->createNavigationQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNode()
    {
        return $this->getFactory()->createNavigationNodeQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $fkUrl
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodeByFkUrl($fkUrl)
    {
        return $this->queryNavigationNode()
        ->useSpyNavigationNodeLocalizedAttributesQuery()
            ->filterByFkUrl($fkUrl)
        ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $fkUrl
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributesQuery
     */
    public function queryNavigationNodeLocalizedAttributesByFkUrl($fkUrl)
    {
        return $this->getFactory()
            ->createNavigationNodeLocalizedAttributesQuery()
            ->filterByFkUrl($fkUrl);
    }
}
