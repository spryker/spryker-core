<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use Generated\Shared\Transfer\NavigationTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigation;

class NavigationCreator implements NavigationCreatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function createNavigation(NavigationTransfer $navigationTransfer)
    {
        $this->assertNavigationForCreate($navigationTransfer);

        $navigationTransfer = $this->persistNavigation($navigationTransfer);
        // TODO: touch

        return $navigationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    protected function assertNavigationForCreate(NavigationTransfer $navigationTransfer)
    {
        $navigationTransfer
            ->requireKey()
            ->requireName();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    protected function persistNavigation(NavigationTransfer $navigationTransfer)
    {
        $navigationEntity = $this->createEntityFromTransfer($navigationTransfer);
        $navigationEntity->save();

        $navigationTransfer->fromArray($navigationEntity->toArray(), true);

        return $navigationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigation
     */
    protected function createEntityFromTransfer(NavigationTransfer $navigationTransfer)
    {
        $navigationEntity = new SpyNavigation();
        $navigationEntity->fromArray($navigationTransfer->modifiedToArray());

        return $navigationEntity;
    }

}
