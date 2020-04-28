<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use ArrayObject;
use Generated\Shared\Transfer\NavigationErrorTransfer;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationResponseTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface;
use Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface;

class NavigationDuplicator implements NavigationDuplicatorInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_NAVIGATION_TREE_NOT_FOUND = 'Navigation tree transfer is not found';
    protected const ERROR_MESSAGE_NAVIGATION_KEY_ALREADY_EXISTS = 'Navigation with the same key already exists.';

    /**
     * @var \Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface
     */
    protected $navigationTreeReader;

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
     * @param \Spryker\Zed\Navigation\Business\Navigation\NavigationCreatorInterface $navigationCreator
     * @param \Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface $navigationNodeCreator
     */
    public function __construct(
        NavigationTreeReaderInterface $navigationTreeReader,
        NavigationCreatorInterface $navigationCreator,
        NavigationNodeCreatorInterface $navigationNodeCreator
    ) {
        $this->navigationTreeReader = $navigationTreeReader;
        $this->navigationCreator = $navigationCreator;
        $this->navigationNodeCreator = $navigationNodeCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $newNavigationElement
     * @param \Generated\Shared\Transfer\NavigationTransfer $baseNavigationElement
     *
     * @return \Generated\Shared\Transfer\NavigationResponseTransfer
     */
    public function duplicateNavigation(
        NavigationTransfer $newNavigationElement,
        NavigationTransfer $baseNavigationElement
    ): NavigationResponseTransfer {
        $newNavigationElement
            ->requireKey()
            ->requireName();

        $navigationResponseTransfer = (new NavigationResponseTransfer())->setIsSuccessful(false);
        $navigationTreeTransfer = $this->navigationTreeReader->findNavigationTree($baseNavigationElement);
        if (!$navigationTreeTransfer) {
            return $navigationResponseTransfer
                ->setError((new NavigationErrorTransfer())->setMessage(static::ERROR_MESSAGE_NAVIGATION_TREE_NOT_FOUND));
        }

        if (!$this->hasUniqueKey($newNavigationElement->getKey())) {
            return $navigationResponseTransfer
                ->setError((new NavigationErrorTransfer())->setMessage(static::ERROR_MESSAGE_NAVIGATION_KEY_ALREADY_EXISTS));
        }

        $newNavigationElement->setIsActive($navigationTreeTransfer->getNavigation()->getIsActive());

        return $this->getTransactionHandler()->handleTransaction(function () use ($newNavigationElement, $navigationTreeTransfer) {
            return $this->executeDuplicateNavigationTransaction($newNavigationElement, $navigationTreeTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     * @param \Generated\Shared\Transfer\NavigationTreeTransfer $navigationTreeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationResponseTransfer
     */
    protected function executeDuplicateNavigationTransaction(
        NavigationTransfer $navigationTransfer,
        NavigationTreeTransfer $navigationTreeTransfer
    ): NavigationResponseTransfer {
        $navigationTransfer = $this->navigationCreator->createNavigation($navigationTransfer);
        $this->duplicateNavigationNodeTransfers($navigationTreeTransfer->getNodes(), $navigationTransfer->getIdNavigation());

        return (new NavigationResponseTransfer())
            ->setIsSuccessful(true)
            ->setNavigationTransfer($navigationTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\NavigationTreeNodeTransfer[] $navigationTreeNodeTransfers
     * @param int $idNavigation
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer|null $parentNavigationNodeTransfer
     *
     * @return void
     */
    protected function duplicateNavigationNodeTransfers(
        ArrayObject $navigationTreeNodeTransfers,
        int $idNavigation,
        ?NavigationNodeTransfer $parentNavigationNodeTransfer = null
    ): void {
        if (!$navigationTreeNodeTransfers->count()) {
            return;
        }

        foreach ($navigationTreeNodeTransfers as $navigationTreeNodeTransfer) {
            $navigationNodeTransfer = $navigationTreeNodeTransfer->getNavigationNode();
            $newNavigationNodeLocalizedAttributesTransfers = $this->duplicateNavigationNodeLocalizedAttributesTransfers(
                $navigationNodeTransfer->getNavigationNodeLocalizedAttributes()
            );

            $idParentNavigationNode = $parentNavigationNodeTransfer ? $parentNavigationNodeTransfer->getIdNavigationNode() : null;
            $navigationNodeTransferForDuplication = clone $navigationNodeTransfer
                ->setIdNavigationNode(null)
                ->setFkNavigation($idNavigation)
                ->setFkParentNavigationNode($idParentNavigationNode)
                ->setNavigationNodeLocalizedAttributes($newNavigationNodeLocalizedAttributesTransfers);

            $this->duplicateNavigationNodeTransfers(
                $navigationTreeNodeTransfer->getChildren(),
                $idNavigation,
                $this->navigationNodeCreator->createNavigationNode($navigationNodeTransferForDuplication)
            );
        }
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

    /**
     * @param string $navigationKey
     *
     * @return bool
     */
    protected function hasUniqueKey(string $navigationKey)
    {
        return true;
    }
}
