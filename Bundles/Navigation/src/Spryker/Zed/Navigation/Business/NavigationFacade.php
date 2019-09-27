<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Navigation\Business\NavigationBusinessFactory getFactory()
 */
class NavigationFacade extends AbstractFacade implements NavigationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function createNavigation(NavigationTransfer $navigationTransfer)
    {
        return $this->getFactory()
            ->createNavigationCreator()
            ->createNavigation($navigationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function updateNavigation(NavigationTransfer $navigationTransfer)
    {
        return $this->getFactory()
            ->createNavigationUpdater()
            ->updateNavigation($navigationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer|null
     */
    public function findNavigation(NavigationTransfer $navigationTransfer)
    {
        return $this->getFactory()
            ->createNavigationReader()
            ->findNavigation($navigationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    public function deleteNavigation(NavigationTransfer $navigationTransfer)
    {
        $this->getFactory()
            ->createNavigationDeleter()
            ->deleteNavigation($navigationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function createNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->getFactory()
            ->createNavigationNodeCreator()
            ->createNavigationNode($navigationNodeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function updateNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->getFactory()
            ->createNavigationNodeUpdater()
            ->updateNavigationNode($navigationNodeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer|null
     */
    public function findNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->getFactory()
            ->createNavigationNodeReader()
            ->findNavigationNode($navigationNodeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    public function deleteNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $this->getFactory()
            ->createNavigationNodeDeleter()
            ->deleteNavigationNode($navigationNodeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTreeTransfer|null
     */
    public function findNavigationTree(NavigationTransfer $navigationTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createNavigationTreeReader()
            ->findNavigationTree($navigationTransfer, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTreeTransfer $navigationTreeTransfer
     *
     * @return void
     */
    public function updateNavigationTreeHierarchy(NavigationTreeTransfer $navigationTreeTransfer)
    {
        $this->getFactory()
            ->createNavigationTreeHierarchyUpdater()
            ->updateNavigationTreeHierarchy($navigationTreeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function touchNavigationByUrl(UrlTransfer $urlTransfer)
    {
        $this->getFactory()
            ->createNavigationTouch()
            ->touchActiveByUrl($urlTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function detachUrlFromNavigationNodes(UrlTransfer $urlTransfer)
    {
        $this->getFactory()
            ->createNavigationNodeUrlCleaner()
            ->detachUrlFromNavigationNodes($urlTransfer);
    }
}
