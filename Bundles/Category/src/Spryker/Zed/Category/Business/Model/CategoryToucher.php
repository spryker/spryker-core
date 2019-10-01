<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryToucher implements CategoryToucherInterface
{
    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface $touchFacade
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     */
    public function __construct(CategoryToTouchInterface $touchFacade, CategoryQueryContainerInterface $queryContainer)
    {
        $this->touchFacade = $touchFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function touchCategoryNodeActiveRecursively($idCategoryNode)
    {
        foreach ($this->getRelatedNodes($idCategoryNode) as $relatedNodeEntity) {
            if ($relatedNodeEntity->getFkCategoryNode() !== $idCategoryNode) {
                $this->touchCategoryNodeActive($relatedNodeEntity->getFkCategoryNode());
            }

            if ($relatedNodeEntity->getFkCategoryNodeDescendant() !== $idCategoryNode) {
                $this->touchCategoryNodeActive($relatedNodeEntity->getFkCategoryNodeDescendant());
            }
        }

        $this->touchCategoryNodeActive($idCategoryNode);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTable[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getRelatedNodes($idCategoryNode)
    {
        $relatedNodeCollection = $this
            ->queryContainer
            ->queryClosureTableByNodeId($idCategoryNode)
            ->find();

        return $relatedNodeCollection;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function touchCategoryNodeActive($idCategoryNode)
    {
        $this->touchFacade->touchActive(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
        $this->touchNavigationActive($idCategoryNode);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function touchCategoryNodeDeletedRecursively($idCategoryNode)
    {
        foreach ($this->getRelatedNodes($idCategoryNode) as $relatedNodeEntity) {
            $this->touchCategoryNodeDeleted($relatedNodeEntity->getFkCategoryNodeDescendant());
        }

        $this->touchCategoryNodeDeleted($idCategoryNode);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function touchCategoryNodeDeleted($idCategoryNode)
    {
        $this->touchFacade->touchDeleted(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
        $this->touchNavigationActive($idCategoryNode);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    protected function touchNavigationActive($idCategoryNode)
    {
        $rootNodeEntity = $this->findRootNode($idCategoryNode);

        if ($rootNodeEntity) {
            $this->touchFacade->touchActive(
                CategoryConfig::RESOURCE_TYPE_NAVIGATION,
                $rootNodeEntity->getIdCategoryNode()
            );
        }
    }

    /**
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode|null
     */
    protected function findRootNode($idCategoryNode)
    {
        $categoryNodeEntity = $this
            ->queryContainer
            ->queryRootNode()
            ->useClosureTableQuery()
                ->filterByFkCategoryNodeDescendant($idCategoryNode)
            ->endUse()
            ->findOne();

        return $categoryNodeEntity;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function touchCategoryActive($idCategory)
    {
        $categoryNodeCollection = $this
            ->queryContainer
            ->queryAllNodesByCategoryId($idCategory)
            ->find();

        foreach ($categoryNodeCollection as $categoryNodeEntity) {
            $this->touchCategoryNodeActive($categoryNodeEntity->getIdCategoryNode());
        }
    }

    /**
     * @param int $idFormerParentCategoryNode
     *
     * @return void
     */
    public function touchFormerParentCategoryNodeActiveRecursively($idFormerParentCategoryNode)
    {
        foreach ($this->findParentCategoryNodes($idFormerParentCategoryNode) as $parentNodeEntity) {
            if ($parentNodeEntity->getFkCategoryNode() !== $idFormerParentCategoryNode) {
                $this->touchCategoryNodeActive($parentNodeEntity->getFkCategoryNode());
            }
        }

        $this->touchCategoryNodeActive($idFormerParentCategoryNode);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTable[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findParentCategoryNodes($idCategoryNode)
    {
        return $this
            ->queryContainer
            ->queryClosureTableFilterByIdNodeDescendant($idCategoryNode)
            ->find();
    }
}
