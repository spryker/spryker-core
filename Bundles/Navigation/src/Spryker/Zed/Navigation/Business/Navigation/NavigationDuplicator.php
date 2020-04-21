<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use Generated\Shared\Transfer\NavigationTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Spryker\Zed\Navigation\Business\Mapper\NavigationNodeMapperInterface;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface;
use Spryker\Zed\Navigation\Persistence\NavigationRepositoryInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class NavigationDuplicator implements NavigationDuplicatorInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationRepositoryInterface
     */
    protected $navigationRepository;

    /**
     * @var \Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface
     */
    protected $navigationTouch;

    /**
     * @var \Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface
     */
    protected $navigationNodeCreator;

    /**
     * @var \Spryker\Zed\Navigation\Business\Mapper\NavigationNodeMapperInterface
     */
    protected $navigationNodeMapper;

    /**
     * @param \Spryker\Zed\Navigation\Persistence\NavigationRepositoryInterface $navigationRepository
     * @param \Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface $navigationTouch
     * @param \Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface $navigationNodeCreator
     * @param \Spryker\Zed\Navigation\Business\Mapper\NavigationNodeMapperInterface $navigationNodeMapper
     */
    public function __construct(
        NavigationRepositoryInterface $navigationRepository,
        NavigationTouchInterface $navigationTouch,
        NavigationNodeCreatorInterface $navigationNodeCreator,
        NavigationNodeMapperInterface $navigationNodeMapper
    ) {
        $this->navigationRepository = $navigationRepository;
        $this->navigationTouch = $navigationTouch;
        $this->navigationNodeCreator = $navigationNodeCreator;
        $this->navigationNodeMapper = $navigationNodeMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $newNavigationElement
     * @param \Generated\Shared\Transfer\NavigationTransfer $baseNavigationElement
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function duplicateNavigation(
        NavigationTransfer $newNavigationElement,
        NavigationTransfer $baseNavigationElement
    ): NavigationTransfer {
        $this->assertNavigationForCreate($newNavigationElement);
        $this->assertNavigationForCreate($baseNavigationElement);

        $newNavigationElement
            ->setName($baseNavigationElement->getName())
            ->setIsActive($baseNavigationElement->getIsActive());

        $navigationNodeTransfers = $this->navigationRepository->getNavigationNodesByNavigationId(
            $baseNavigationElement->getIdNavigation()
        );

        $newNavigationNodeTransfers = $this->navigationNodeMapper->mapToNavigationNodeTransfers($navigationNodeTransfers);

        return $this->handleDatabaseTransaction(function () use ($newNavigationElement, $newNavigationNodeTransfers) {
            return $this->executeCreateNavigationTransaction($newNavigationElement, $newNavigationNodeTransfers);
        });
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
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer[] $navigationNodeTransfers
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    protected function executeCreateNavigationTransaction(
        NavigationTransfer $navigationTransfer,
        array $navigationNodeTransfers
    ): NavigationTransfer {
        $navigationTransfer = $this->persistNavigation($navigationTransfer);
        $this->navigationTouch->touchActive($navigationTransfer);

        foreach ($navigationNodeTransfers as $navigationNodeTransfer) {
            $this->navigationNodeCreator->createNavigationNode(
                $navigationNodeTransfer->setFkNavigation($navigationTransfer->getIdNavigation())
            );
        }

        return $navigationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    protected function persistNavigation(NavigationTransfer $navigationTransfer): NavigationTransfer
    {
        $navigationEntity = new SpyNavigation();
        $navigationEntity->fromArray($navigationTransfer->modifiedToArray());
        $navigationEntity->save();

        return $this->hydrateNavigationTransfer($navigationTransfer, $navigationEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    protected function hydrateNavigationTransfer(NavigationTransfer $navigationTransfer, SpyNavigation $navigationEntity)
    {
        $navigationTransfer->fromArray($navigationEntity->toArray(), true);

        return $navigationTransfer;
    }
}
