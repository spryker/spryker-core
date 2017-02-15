<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Dependency\QueryContainer;

use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;

class NavigationGuiToNavigationBridge implements NavigationGuiToNavigationInterface
{

    /**
     * @var NavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @param NavigationQueryContainerInterface $navigationQueryContainer
     */
    public function __construct($navigationQueryContainer)
    {
        $this->navigationQueryContainer = $navigationQueryContainer;
    }

    /**
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation()
    {
        return $this->navigationQueryContainer->queryNavigation();
    }

}
