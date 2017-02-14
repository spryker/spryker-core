<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Tree;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeNodeTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;
use Orm\Zed\Navigation\Persistence\Base\SpyNavigationNodeLocalizedAttributes;
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

        return $this->mapNavigationEntityToNavigationTreeTransfer($navigationEntity);
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
            ->queryNavigationById($navigationTransfer->getIdNavigation())
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return \Generated\Shared\Transfer\NavigationTreeTransfer
     */
    protected function mapNavigationEntityToNavigationTreeTransfer(SpyNavigation $navigationEntity)
    {
        $navigationTreeTransfer = new NavigationTreeTransfer();

        $navigationTransfer = $this->mapNavigationEntityToTransfer($navigationEntity);
        $navigationTreeTransfer->setNavigation($navigationTransfer);

        $rootNavigationNodes = $this->findRootNavigationNodes($navigationEntity);
        foreach ($rootNavigationNodes as $navigationNodeEntity) {
            $navigationTreeNodeTransfer = $this->getNavigationTreeNodeRecursively($navigationNodeEntity);
            $navigationTreeTransfer->addNode($navigationTreeNodeTransfer);
        }

        return $navigationTreeTransfer;
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
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findRootNavigationNodes(SpyNavigation $navigationEntity)
    {
        return $this->navigationQueryContainer
            ->queryRootNavigationNodesByIdNavigation($navigationEntity->getIdNavigation())
            ->find();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     *
     * @return \Generated\Shared\Transfer\NavigationTreeNodeTransfer
     */
    protected function getNavigationTreeNodeRecursively(SpyNavigationNode $navigationNodeEntity)
    {
        $navigationTreeNodeTransfer = new NavigationTreeNodeTransfer();

        $navigationNodeTransfer = $this->mapNavigationNodeEntityToTransfer($navigationNodeEntity);
        $navigationTreeNodeTransfer->setNavigationNode($navigationNodeTransfer);

        $childrenNavigationNodeEntities = $this->findChildrenNavigationNodes($navigationNodeTransfer);
        foreach ($childrenNavigationNodeEntities as $childrenNavigationNodeEntity) {
            $childNavigationTreeNodeTransfer = $this->getNavigationTreeNodeRecursively($childrenNavigationNodeEntity);
            $navigationTreeNodeTransfer->addChild($childNavigationTreeNodeTransfer);
        }

        return $navigationTreeNodeTransfer;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function mapNavigationNodeEntityToTransfer(SpyNavigationNode $navigationNodeEntity)
    {
        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer->fromArray($navigationNodeEntity->toArray(), true);

        foreach ($navigationNodeEntity->getSpyNavigationNodeLocalizedAttributess() as $navigationNodeLocalizedAttributesEntity) {
            $navigationNodeLocalizedAttributesTransfer = $this->mapNavigationNodeLocalizedAttributesEntityToTransfer($navigationNodeLocalizedAttributesEntity);
            $navigationNodeTransfer->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\Base\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributesEntity
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    protected function mapNavigationNodeLocalizedAttributesEntityToTransfer(SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributesEntity)
    {
        $navigationNodeLocalizedAttributesTransfer = new NavigationNodeLocalizedAttributesTransfer();
        $navigationNodeLocalizedAttributesTransfer->fromArray($navigationNodeLocalizedAttributesEntity->toArray(), true);

        return $navigationNodeLocalizedAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findChildrenNavigationNodes(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->navigationQueryContainer
            ->queryNavigationNodesByFkParentNavigationNode($navigationNodeTransfer->getIdNavigationNode())
            ->find();
    }

}
