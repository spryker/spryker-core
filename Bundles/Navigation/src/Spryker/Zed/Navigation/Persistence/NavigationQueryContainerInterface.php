<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

/**
 * @method \Spryker\Zed\Navigation\Persistence\NavigationPersistenceFactory getFactory()
 */
interface NavigationQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param int $idNavigation
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigationById($idNavigation);

    /**
     * @api
     *
     * @param int $idNavigationNode
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodeById($idNavigationNode);

    /**
     * @api
     *
     * @param int $idNavigationNodeLocalizedAttributes
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributesQuery
     */
    public function queryNavigationNodeLocalizedAttributesById($idNavigationNodeLocalizedAttributes);

    /**
     * @api
     *
     * @param int $idNavigation
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryRootNavigationNodesByIdNavigation($idNavigation);

    /**
     * @api
     *
     * @param int $fkParentNavigationNode
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodesByFkParentNavigationNode($fkParentNavigationNode);

    /**
     * @api
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation();

    /**
     * @api
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNode();

    /**
     * @api
     *
     * @param int $fkUrl
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodeByFkUrl($fkUrl);

    /**
     * @api
     *
     * @param int $fkUrl
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributesQuery
     */
    public function queryNavigationNodeLocalizedAttributesByFkUrl($fkUrl);

}
