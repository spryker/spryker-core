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
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface;
use Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface;

class NavigationDuplicator implements NavigationDuplicatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface
     */
    protected $navigationTreeReader;

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
     * @param \Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface $navigationTreeReader
     * @param \Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface $navigationTouch
     * @param \Spryker\Zed\Navigation\Business\Navigation\NavigationCreatorInterface $navigationCreator
     * @param \Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface $navigationNodeCreator
     */
    public function __construct(
        NavigationTreeReaderInterface $navigationTreeReader,
        NavigationTouchInterface $navigationTouch,
        NavigationCreatorInterface $navigationCreator,
        NavigationNodeCreatorInterface $navigationNodeCreator
    ) {
        $this->navigationTreeReader = $navigationTreeReader;
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

        $navigationTreeTransfer = $this->navigationTreeReader->findNavigationTree($baseNavigationElement);
        $baseNavigationNodeTransfers = $this->getNavigationNodeTransfersRecursively($navigationTreeTransfer->getNodes());
        $newNavigationNodeTransfers = $this->duplicateNavigationNodeTransfers($baseNavigationNodeTransfers);

        return $this->getTransactionHandler()->handleTransaction(function () use ($newNavigationElement, $newNavigationNodeTransfers) {
            return $this->executeCreateNavigationTransaction($newNavigationElement, $newNavigationNodeTransfers);
        });
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\NavigationTreeNodeTransfer[] $navigationTreeNodeTransfers
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer[] $navigationNodeTransfers
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer[]
     */
    protected function getNavigationNodeTransfersRecursively(
        ArrayObject $navigationTreeNodeTransfers,
        array $navigationNodeTransfers = []
    ): array {
        foreach ($navigationTreeNodeTransfers as $navigationTreeNodeTransfer) {
            $navigationNodeTransfers[] = $navigationTreeNodeTransfer->getNavigationNode();
            $navigationNodeTransfers = $this->getNavigationNodeTransfersRecursively(
                $navigationTreeNodeTransfer->getChildren(),
                $navigationNodeTransfers
            );
        }

        return $navigationNodeTransfers;
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
            $navigationNodeTransfer->setIdNavigationNode(null);
            $navigationNodeTransfer->setNavigationNodeLocalizedAttributes(new ArrayObject());
            $newNavigationNodeTransfers[] = (new NavigationNodeTransfer())->fromArray(
                $navigationNodeTransfer->toArray(),
                true
            )->setNavigationNodeLocalizedAttributes($newNavigationNodeLocalizedAttributesTransfers);
        }

        return $newNavigationNodeTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer[] $navigationNodeLocalizedAttributesTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer[]
     */
    protected function duplicateNavigationNodeLocalizedAttributesTransfers(ArrayObject $navigationNodeLocalizedAttributesTransfers): ArrayObject
    {
        $newNavigationNodeLocalizedAttributesTransfers = [];
        foreach ($navigationNodeLocalizedAttributesTransfers as $navigationNodeLocalizedAttributesTransfer) {
            $navigationNodeLocalizedAttributesTransfer->setIdNavigationNodeLocalizedAttributes(null);
            $newNavigationNodeLocalizedAttributesTransfers[] = (new NavigationNodeLocalizedAttributesTransfer())->fromArray(
                $navigationNodeLocalizedAttributesTransfer->toArray(),
                true
            );
        }

        return new ArrayObject($newNavigationNodeLocalizedAttributesTransfers);
    }
}
