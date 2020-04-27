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
use Spryker\Zed\Navigation\Persistence\NavigationEntityManagerInterface;

class NavigationDuplicator implements NavigationDuplicatorInterface
{
    use TransactionTrait;

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
     * @var \Spryker\Zed\Navigation\Persistence\NavigationEntityManagerInterface
     */
    protected $navigationEntityManager;

    /**
     * @param \Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface $navigationTreeReader
     * @param \Spryker\Zed\Navigation\Business\Navigation\NavigationCreatorInterface $navigationCreator
     * @param \Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface $navigationNodeCreator
     * @param \Spryker\Zed\Navigation\Persistence\NavigationEntityManagerInterface $navigationEntityManager
     */
    public function __construct(
        NavigationTreeReaderInterface $navigationTreeReader,
        NavigationCreatorInterface $navigationCreator,
        NavigationNodeCreatorInterface $navigationNodeCreator,
        NavigationEntityManagerInterface $navigationEntityManager
    ) {
        $this->navigationTreeReader = $navigationTreeReader;
        $this->navigationCreator = $navigationCreator;
        $this->navigationNodeCreator = $navigationNodeCreator;
        $this->navigationEntityManager = $navigationEntityManager;
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

        $navigationTreeTransfer = $this->navigationTreeReader->findNavigationTree($baseNavigationElement);
        $newNavigationElement->setIsActive($navigationTreeTransfer->getNavigation()->getIsActive());
        $baseNavigationNodeTransfers = $this->getNavigationNodeTransfersRecursively($navigationTreeTransfer->getNodes());

        return $this->getTransactionHandler()->handleTransaction(function () use ($newNavigationElement, $baseNavigationNodeTransfers) {
            return $this->executeDuplicateNavigationTransaction($newNavigationElement, $baseNavigationNodeTransfers);
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
    protected function executeDuplicateNavigationTransaction(
        NavigationTransfer $navigationTransfer,
        array $navigationNodeTransfers
    ): NavigationTransfer {
        $navigationTransfer = $this->navigationCreator->createNavigation($navigationTransfer);
        $newNavigationNodeTransfers = $this->duplicateNavigationNodeTransfers($navigationNodeTransfers);

        $duplicatedNavigationNodeIdsByNavigationNodeIds = [];
        foreach ($newNavigationNodeTransfers as $index => $navigationNodeTransfer) {
            $newNavigationNode = $this->navigationNodeCreator->createNavigationNode(
                $navigationNodeTransfer->setFkNavigation($navigationTransfer->getIdNavigation())
            );

            $duplicatedNavigationNodeIdsByNavigationNodeIds[$navigationNodeTransfers[$index]->getIdNavigationNode()]
                = $newNavigationNode->getIdNavigationNode();
        }

        $this->navigationEntityManager
            ->updateFkParentNavigationNodeForDuplicatedNavigationNodes($duplicatedNavigationNodeIdsByNavigationNodeIds);

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
            $navigationNodeTransferForDuplication = clone $navigationNodeTransfer;
            $navigationNodeTransferForDuplication->setIdNavigationNode(null);
            $navigationNodeTransferForDuplication->setNavigationNodeLocalizedAttributes(new ArrayObject());
            $newNavigationNodeTransfers[] = (new NavigationNodeTransfer())->fromArray(
                $navigationNodeTransferForDuplication->toArray(),
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
