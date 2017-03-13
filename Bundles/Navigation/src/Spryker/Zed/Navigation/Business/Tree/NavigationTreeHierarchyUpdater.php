<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Tree;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTreeNodeTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;
use Spryker\Zed\Navigation\Business\Exception\NavigationNodeNotFoundException;
use Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;

class NavigationTreeHierarchyUpdater implements NavigationTreeHierarchyUpdaterInterface
{

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
     * @param \Generated\Shared\Transfer\NavigationTreeTransfer $navigationTreeTransfer
     *
     * @return void
     */
    public function updateNavigationTreeHierarchy(NavigationTreeTransfer $navigationTreeTransfer)
    {
        $this->assertNavigationTreeForUpdate($navigationTreeTransfer);

        $this->navigationQueryContainer->getConnection()->beginTransaction();

        $this->persistNavigationTree($navigationTreeTransfer);
        $this->navigationTouch->touchActive($navigationTreeTransfer->getNavigation());

        $this->navigationQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeTransfer $navigationTreeTransfer
     *
     * @return void
     */
    protected function assertNavigationTreeForUpdate(NavigationTreeTransfer $navigationTreeTransfer)
    {
        $navigationTreeTransfer->requireNodes();
        $navigationTreeTransfer->requireNavigation();

        foreach ($navigationTreeTransfer->getNodes() as $navigationTreeNodeTransfer) {
            $this->assertNavigationTreeNodeRecursively($navigationTreeNodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeNodeTransfer $navigationTreeNodeTransfer
     *
     * @return void
     */
    protected function assertNavigationTreeNodeRecursively(NavigationTreeNodeTransfer $navigationTreeNodeTransfer)
    {
        $navigationTreeNodeTransfer
            ->requireNavigationNode()
            ->getNavigationNode()
                ->requireIdNavigationNode();

        foreach ($navigationTreeNodeTransfer->getChildren() as $childNavigationTreeNodeTransfer) {
            $this->assertNavigationTreeNodeRecursively($childNavigationTreeNodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeTransfer $navigationTreeTransfer
     * @param int|null $fkParentNavigationNode
     *
     * @return void
     */
    protected function persistNavigationTree(NavigationTreeTransfer $navigationTreeTransfer, $fkParentNavigationNode = null)
    {
        foreach ($navigationTreeTransfer->getNodes() as $navigationTreeNodeTransfer) {
            $this->persistNavigationTreeNodeRecursively($navigationTreeNodeTransfer, $fkParentNavigationNode);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeNodeTransfer $navigationTreeNodeTransfer
     * @param int $fkParentNavigationNode
     *
     * @return void
     */
    protected function persistNavigationTreeNodeRecursively(NavigationTreeNodeTransfer $navigationTreeNodeTransfer, $fkParentNavigationNode)
    {
        $navigationNodeTransfer = $navigationTreeNodeTransfer->getNavigationNode();
        $navigationNodeEntity = $this->getNavigationNodeEntity($navigationNodeTransfer);
        $navigationNodeEntity
            ->setPosition($navigationNodeTransfer->getPosition())
            ->setFkParentNavigationNode($fkParentNavigationNode)
            ->save();

        foreach ($navigationTreeNodeTransfer->getChildren() as $childNavigationTreeNodeTransfer) {
            $this->persistNavigationTreeNodeRecursively($childNavigationTreeNodeTransfer, $navigationNodeEntity->getIdNavigationNode());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @throws \Spryker\Zed\Navigation\Business\Exception\NavigationNodeNotFoundException
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode
     */
    protected function getNavigationNodeEntity(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeEntity = $this->navigationQueryContainer
            ->queryNavigationNodeById($navigationNodeTransfer->getIdNavigationNode())
            ->findOne();

        if (!$navigationNodeEntity) {
            throw new NavigationNodeNotFoundException(sprintf(
                'Navigation node entity not found with ID %d.',
                $navigationNodeTransfer->getIdNavigationNode()
            ));
        }

        return $navigationNodeEntity;
    }

}
