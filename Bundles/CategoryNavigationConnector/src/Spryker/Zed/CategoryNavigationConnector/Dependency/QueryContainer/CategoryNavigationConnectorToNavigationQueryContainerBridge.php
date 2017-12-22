<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer;

class CategoryNavigationConnectorToNavigationQueryContainerBridge implements CategoryNavigationConnectorToNavigationQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @param \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface $navigationQueryContainer
     */
    public function __construct($navigationQueryContainer)
    {
        $this->navigationQueryContainer = $navigationQueryContainer;
    }

    /**
     * @param int $fkUrl
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    public function queryNavigationNodeByFkUrl($fkUrl)
    {
        return $this->navigationQueryContainer->queryNavigationNodeByFkUrl($fkUrl);
    }
}
