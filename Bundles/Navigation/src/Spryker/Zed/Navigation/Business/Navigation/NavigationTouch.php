<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Shared\Navigation\NavigationConfig;
use Spryker\Zed\Navigation\Dependency\Facade\NavigationToTouchInterface;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;

class NavigationTouch implements NavigationTouchInterface
{

    /**
     * @var \Spryker\Zed\Navigation\Dependency\Facade\NavigationToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @param \Spryker\Zed\Navigation\Dependency\Facade\NavigationToTouchInterface $touchFacade
     * @param \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface $navigationQueryContainer
     */
    public function __construct(NavigationToTouchInterface $touchFacade, NavigationQueryContainerInterface $navigationQueryContainer)
    {
        $this->touchFacade = $touchFacade;
        $this->navigationQueryContainer = $navigationQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return bool
     */
    public function touchActive(NavigationTransfer $navigationTransfer)
    {
        $idNavigation = $navigationTransfer
            ->requireIdNavigation()
            ->getIdNavigation();

        return $this->touchFacade->touchActive(NavigationConfig::RESOURCE_TYPE_NAVIGATION_MENU, $idNavigation);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return bool
     */
    public function touchDeleted(NavigationTransfer $navigationTransfer)
    {
        $idNavigation = $navigationTransfer
            ->requireIdNavigation()
            ->getIdNavigation();

        return $this->touchFacade->touchDeleted(NavigationConfig::RESOURCE_TYPE_NAVIGATION_MENU, $idNavigation);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function touchActiveByUrl(UrlTransfer $urlTransfer)
    {
        $urlTransfer->requireIdUrl();

        $navigationNodeEntities = $this->navigationQueryContainer
            ->queryNavigationNodeByFkUrl($urlTransfer->getIdUrl())
            ->find();

        foreach ($navigationNodeEntities as $navigationNodeEntity) {
            $this->touchFacade->touchActive(NavigationConfig::RESOURCE_TYPE_NAVIGATION_MENU, $navigationNodeEntity->getFkNavigation());
        }
    }

}
