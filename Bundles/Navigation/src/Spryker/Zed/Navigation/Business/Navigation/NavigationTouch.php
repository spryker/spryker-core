<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Shared\Navigation\NavigationConfig;
use Spryker\Zed\Navigation\Dependency\NavigationToTouchInterface;

class NavigationTouch implements NavigationTouchInterface
{

    /**
     * @var \Spryker\Zed\Navigation\Dependency\NavigationToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\Navigation\Dependency\NavigationToTouchInterface $touchFacade
     */
    public function __construct(NavigationToTouchInterface $touchFacade)
    {
        $this->touchFacade = $touchFacade;
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

}
