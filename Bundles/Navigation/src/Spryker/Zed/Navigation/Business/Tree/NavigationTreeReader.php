<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Tree;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeNodeTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;

class NavigationTreeReader implements NavigationTreeReaderInterface
{

    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @param \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface $navigationQueryContainer
     */
    public function __construct(NavigationQueryContainerInterface $navigationQueryContainer)
    {
        $this->navigationQueryContainer = $navigationQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTreeTransfer
     */
    public function findNavigationTree(NavigationTransfer $navigationTransfer)
    {
        $this->assertNavigationForRead($navigationTransfer);

        $navigationEntity = $this->findNavigationEntity($navigationTransfer);

        if (!$navigationEntity) {
            return null;
        }

        $navigationTreeTransfer = new NavigationTreeTransfer();

        $navigationTransfer = $this->mapNavigationEntityToTransfer($navigationEntity);
        $navigationTreeTransfer->setNavigation($navigationTransfer);

        foreach ($navigationEntity->getSpyNavigationNodes() as $navigationNodeEntity) {
            $navigationTreeNodeTransfer = $this->getNavigationTreeNode($navigationNodeEntity);

            $navigationTreeTransfer->addNode($navigationTreeNodeTransfer);
        }

        return $navigationTreeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    protected function assertNavigationForRead(NavigationTransfer $navigationTransfer)
    {
        $navigationTransfer->requireIdNavigation();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigation
     */
    protected function findNavigationEntity(NavigationTransfer $navigationTransfer)
    {
        return $this->navigationQueryContainer
            ->queryNavigationById($navigationTransfer->getIdNavigation()) // todo: try to reduce queries to minimum
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    protected function mapNavigationEntityToTransfer(SpyNavigation $navigationEntity)
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->fromArray($navigationEntity->toArray(), true);

        return $navigationTransfer;
    }

    /**
     * @param SpyNavigationNode $navigationNodeEntity
     *
     * @return NavigationTreeNodeTransfer
     */
    protected function getNavigationTreeNode(SpyNavigationNode $navigationNodeEntity)
    {
        $navigationTreeNodeTransfer = new NavigationTreeNodeTransfer();

        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer->fromArray($navigationNodeEntity->toArray(), true);

        $navigationTreeNodeTransfer->setNavigationNode($navigationNodeTransfer);

        // TODO: have to query children in the right order
        foreach ($navigationNodeEntity->getChildrenNavigationNodes() as $childrenNavigationNodeEntity) {
            $childNavigationTreeNodeTransfer = $this->getNavigationTreeNode($childrenNavigationNodeEntity);
            $navigationTreeNodeTransfer->addChild($childNavigationTreeNodeTransfer);
        }

        return $navigationTreeNodeTransfer;
    }

}
