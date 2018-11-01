<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;

class NavigationGuiToNavigationBridge implements NavigationGuiToNavigationInterface
{
    /**
     * @var \Spryker\Zed\Navigation\Business\NavigationFacadeInterface
     */
    protected $navigationFacade;

    /**
     * @param \Spryker\Zed\Navigation\Business\NavigationFacadeInterface $navigationFacade
     */
    public function __construct($navigationFacade)
    {
        $this->navigationFacade = $navigationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function createNavigation(NavigationTransfer $navigationTransfer)
    {
        return $this->navigationFacade->createNavigation($navigationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer|null
     */
    public function findNavigation(NavigationTransfer $navigationTransfer)
    {
        return $this->navigationFacade->findNavigation($navigationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function updateNavigation(NavigationTransfer $navigationTransfer)
    {
        return $this->navigationFacade->updateNavigation($navigationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    public function deleteNavigation(NavigationTransfer $navigationTransfer)
    {
        $this->navigationFacade->deleteNavigation($navigationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function createNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->navigationFacade->createNavigationNode($navigationNodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function updateNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->navigationFacade->updateNavigationNode($navigationNodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer|null
     */
    public function findNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->navigationFacade->findNavigationNode($navigationNodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    public function deleteNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $this->navigationFacade->deleteNavigationNode($navigationNodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTreeTransfer
     */
    public function findNavigationTree(NavigationTransfer $navigationTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        return $this->navigationFacade->findNavigationTree($navigationTransfer, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeTransfer $navigationTreeTransfer
     *
     * @return void
     */
    public function updateNavigationTreeHierarchy(NavigationTreeTransfer $navigationTreeTransfer)
    {
        $this->navigationFacade->updateNavigationTreeHierarchy($navigationTreeTransfer);
    }
}
