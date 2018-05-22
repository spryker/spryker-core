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

/**
 * @method \Spryker\Zed\Navigation\Business\NavigationBusinessFactory getFactory()
 */
interface NavigationFacadeInterface
{
    /**
     * Specification:
     * - Persists new navigation entity to database.
     * - Touches navigation entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function createNavigation(NavigationTransfer $navigationTransfer);

    /**
     * Specification:
     * - Persists navigation entity changes to database.
     * - Touches navigation entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function updateNavigation(NavigationTransfer $navigationTransfer);

    /**
     * Specification:
     * - Finds navigation entity in database by ID.
     * - Returns navigation transfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer|null
     */
    public function findNavigation(NavigationTransfer $navigationTransfer);

    /**
     * Specification:
     * - Deletes navigation entity from database.
     * - Touches navigation entity as deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    public function deleteNavigation(NavigationTransfer $navigationTransfer);

    /**
     * Specification:
     * - Persists new navigation node entity to database.
     * - Persists new navigation node localized attributes to database.
     * - Touches related navigation entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function createNavigationNode(NavigationNodeTransfer $navigationNodeTransfer);

    /**
     * Specification:
     * - Persists navigation node entity changes to database.
     * - Persists navigation node localized attribute changes to database.
     * - Touches related navigation entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function updateNavigationNode(NavigationNodeTransfer $navigationNodeTransfer);

    /**
     * Specification:
     * - Finds navigation node entity in database by ID.
     * - Returns navigation node transfer along with its related localized attributes if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer|null
     */
    public function findNavigationNode(NavigationNodeTransfer $navigationNodeTransfer);

    /**
     * Specification:
     * - Deletes navigation node entity from database.
     * - Touches related navigation entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    public function deleteNavigationNode(NavigationNodeTransfer $navigationNodeTransfer);

    /**
     * Specification:
     * - Finds navigation entity in database by ID.
     * - Returns a fully hydrated navigation tree transfer if found, NULL otherwise.
     * - When locale is provided only the specified locale will be hydrated into the tree.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTreeTransfer|null
     */
    public function findNavigationTree(NavigationTransfer $navigationTransfer, ?LocaleTransfer $localeTransfer = null);

    /**
     * Specification:
     * - Persists hierarchy (position and parent) changes of navigation node entities to database.
     * - Touches related navigation entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationTreeTransfer $navigationTreeTransfer
     *
     * @return void
     */
    public function updateNavigationTreeHierarchy(NavigationTreeTransfer $navigationTreeTransfer);

    /**
     * Specification:
     * - Touches navigation entities as active which contains any node with the provided url.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function touchNavigationByUrl(UrlTransfer $urlTransfer);

    /**
     * Specification:
     * - Unset provided URL entity relation from all navigation nodes.
     * - Affected navigation nodes will be set to inactive.
     * - Touches related navigation entities as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function detachUrlFromNavigationNodes(UrlTransfer $urlTransfer);
}
