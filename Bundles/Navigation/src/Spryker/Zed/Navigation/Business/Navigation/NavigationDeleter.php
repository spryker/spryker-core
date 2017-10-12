<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use Generated\Shared\Transfer\NavigationTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Spryker\Zed\Navigation\Business\Exception\NavigationNotFoundException;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class NavigationDeleter implements NavigationDeleterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @var \Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface
     */
    protected $navigationTouch;

    /**
     * @param \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface $navigationQueryContainer
     * @param \Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface $navigationTouch
     */
    public function __construct(NavigationQueryContainerInterface $navigationQueryContainer, NavigationTouchInterface $navigationTouch)
    {
        $this->navigationQueryContainer = $navigationQueryContainer;
        $this->navigationTouch = $navigationTouch;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    public function deleteNavigation(NavigationTransfer $navigationTransfer)
    {
        $this->assertNavigationForDelete($navigationTransfer);

        $this->handleDatabaseTransaction(function () use ($navigationTransfer) {
            $this->executeDeleteNavigationTransaction($navigationTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    protected function assertNavigationForDelete(NavigationTransfer $navigationTransfer)
    {
        $navigationTransfer->requireIdNavigation();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    protected function executeDeleteNavigationTransaction(NavigationTransfer $navigationTransfer)
    {
        $navigationEntity = $this->getNavigationEntity($navigationTransfer);

        $this->deleteNavigationEntity($navigationEntity);
        $this->navigationTouch->touchDeleted($navigationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @throws \Spryker\Zed\Navigation\Business\Exception\NavigationNotFoundException
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigation
     */
    protected function getNavigationEntity(NavigationTransfer $navigationTransfer)
    {
        $navigationEntity = $this->navigationQueryContainer
            ->queryNavigationById($navigationTransfer->getIdNavigation())
            ->findOne();

        if (!$navigationEntity) {
            throw new NavigationNotFoundException(sprintf(
                'Navigation entity not found with ID %d.',
                $navigationTransfer->getIdNavigation()
            ));
        }

        return $navigationEntity;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return void
     */
    protected function deleteNavigationEntity(SpyNavigation $navigationEntity)
    {
        $navigationEntity->delete();
    }
}
