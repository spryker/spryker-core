<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use ArrayObject;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
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
     * @var \Spryker\Zed\Navigation\Business\Navigation\NavigationCreatorInterface
     */
    protected $navigationCreator;

    /**
     * @var \Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface
     */
    protected $navigationNodeCreator;

    /**
     * @param \Spryker\Zed\Navigation\Persistence\NavigationRepositoryInterface $navigationRepository
     * @param \Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface $navigationTouch
     * @param \Spryker\Zed\Navigation\Business\Navigation\NavigationCreatorInterface $navigationCreator
     * @param \Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface $navigationNodeCreator
     */
    public function __construct(
        NavigationRepositoryInterface $navigationRepository,
        NavigationTouchInterface $navigationTouch,
        NavigationCreatorInterface $navigationCreator,
        NavigationNodeCreatorInterface $navigationNodeCreator
    ) {
        $this->navigationRepository = $navigationRepository;
        $this->navigationTouch = $navigationTouch;
        $this->navigationCreator = $navigationCreator;
        $this->navigationNodeCreator = $navigationNodeCreator;
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
        $newNavigationElement
            ->requireKey()
            ->requireName();

        $newNavigationElement->setIsActive($baseNavigationElement->getIsActive());

        $navigationNodeTransfers = $this->navigationRepository->getNavigationNodesByNavigationId(
            $baseNavigationElement->getIdNavigation()
        );

        $newNavigationNodeTransfers = $this->duplicateNavigationNodeTransfers($navigationNodeTransfers);

        return $this->handleDatabaseTransaction(function () use ($newNavigationElement, $newNavigationNodeTransfers) {
            return $this->executeCreateNavigationTransaction($newNavigationElement, $newNavigationNodeTransfers);
        });
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
        $navigationTransfer = $this->navigationCreator->createNavigation($navigationTransfer);
        $this->navigationTouch->touchActive($navigationTransfer);

        foreach ($navigationNodeTransfers as $navigationNodeTransfer) {
            $this->navigationNodeCreator->createNavigationNode(
                $navigationNodeTransfer->setFkNavigation($navigationTransfer->getIdNavigation())
            );
        }

        return $navigationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer[] $navigationNodeTransfers
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer[]
     */
    protected function duplicateNavigationNodeTransfers(array $navigationNodeTransfers): array
    {
        $newNavigationNodeTransfers = [];
        foreach ($navigationNodeTransfers as $navigationNodeTransfer) {
            $newNavigationNodeLocalizedAttributesTransfers = $this->duplicateNavigationNodeLocalizedAttributesTransfers(
                $navigationNodeTransfer->getNavigationNodeLocalizedAttributes()
            );
            $navigationNodeData = $navigationNodeTransfer->toArray();
            unset($navigationNodeData['id_navigation_node']);
            unset($navigationNodeData['navigation_node_localized_attributes']);
            $newNavigationNodeTransfers[] = (new NavigationNodeTransfer())->fromArray(
                $navigationNodeData,
                true
            )->setNavigationNodeLocalizedAttributes($newNavigationNodeLocalizedAttributesTransfers);
        }

        return $newNavigationNodeTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer[] $navigationNodeLocalizedAttributesTransfers
     *
     * @return \ArrayObject
     */
    protected function duplicateNavigationNodeLocalizedAttributesTransfers(ArrayObject $navigationNodeLocalizedAttributesTransfers): ArrayObject
    {
        $newNavigationNodeLocalizedAttributesTransfers = [];
        foreach ($navigationNodeLocalizedAttributesTransfers as $navigationNodeLocalizedAttributesTransfer) {
            $newNavigationNodeLocalizedAttributesData = $navigationNodeLocalizedAttributesTransfer->toArray();
            unset($newNavigationNodeLocalizedAttributesData['id_navigation_node_localized_attributes']);
            $newNavigationNodeLocalizedAttributesTransfers[] = (new NavigationNodeLocalizedAttributesTransfer())->fromArray(
                $newNavigationNodeLocalizedAttributesData,
                true
            );
        }

        return new ArrayObject($newNavigationNodeLocalizedAttributesTransfers);
    }
}
