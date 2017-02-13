<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Node;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Spryker\Zed\Navigation\Business\Exception\NavigationNodeNotFoundException;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;

class NavigationNodeDeleter implements NavigationNodeDeleterInterface
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
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    public function deleteNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $this->assertNavigationNodeForDelete($navigationNodeTransfer);

        $navigationNodeEntity = $this->getNavigationNodeEntity($navigationNodeTransfer);

        $this->deleteNavigationNodeEntity($navigationNodeEntity);
        // TODO: touch
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    protected function assertNavigationNodeForDelete(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeTransfer->requireIdNavigationNode();
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

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     *
     * @return void
     */
    protected function deleteNavigationNodeEntity(SpyNavigationNode $navigationNodeEntity)
    {
        $navigationNodeEntity->delete();
    }

}
