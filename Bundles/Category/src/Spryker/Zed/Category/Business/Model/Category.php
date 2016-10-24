<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttributeInterface;
use Spryker\Zed\Category\Business\Model\CategoryExtraParents\CategoryExtraParentsInterface;
use Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeInterface;
use Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface;
use Spryker\Zed\Category\Business\Model\Category\CategoryInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class Category
{

    /**
     * @var \Spryker\Zed\Category\Business\Model\Category\CategoryInterface
     */
    protected $category;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeInterface
     */
    protected $categoryNode;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttributeInterface
     */
    protected $categoryAttribute;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface
     */
    protected $categoryUrl;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var array|\Spryker\Zed\Category\Dependency\Plugin\CategoryDeleteRelationPluginInterface
     */
    protected $deletePlugins;

    /**
     * @param \Spryker\Zed\Category\Business\Model\Category\CategoryInterface $category
     * @param \Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeInterface $categoryNode
     * @param \Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttributeInterface $categoryAttribute
     * @param \Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface $categoryUrl
     * @param \Spryker\Zed\Category\Business\Model\CategoryExtraParents\CategoryExtraParentsInterface $categoryExtraParents
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Dependency\Plugin\CategoryDeleteRelationPluginInterface[] $deletePlugins
     */
    public function __construct(
        CategoryInterface $category,
        CategoryNodeInterface $categoryNode,
        CategoryAttributeInterface $categoryAttribute,
        CategoryUrlInterface $categoryUrl,
        CategoryExtraParentsInterface $categoryExtraParents,
        CategoryQueryContainerInterface $queryContainer,
        array $deletePlugins
    ) {
        $this->category = $category;
        $this->categoryNode = $categoryNode;
        $this->categoryAttribute = $categoryAttribute;
        $this->categoryUrl = $categoryUrl;
        $this->categoryExtraParents = $categoryExtraParents;
        $this->queryContainer = $queryContainer;
        $this->deletePlugins = $deletePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $this->category->create($categoryTransfer);
        $this->categoryNode->create($categoryTransfer);
        $this->categoryAttribute->create($categoryTransfer);
        $this->categoryUrl->create($categoryTransfer);
        $this->categoryExtraParents->create($categoryTransfer);

        $this->queryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $this->category->update($categoryTransfer);
        $this->categoryNode->update($categoryTransfer);
        $this->categoryAttribute->update($categoryTransfer);
        $this->categoryUrl->update($categoryTransfer);
        $this->categoryExtraParents->update($categoryTransfer);

        $this->queryContainer->getConnection()->commit();
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $this->categoryAttribute->delete($idCategory);
        $this->categoryUrl->delete($idCategory);
        $this->categoryNode->delete($idCategory);
        $this->categoryExtraParents->delete($idCategory);

        $this->runDeletePlugins($idCategory);

        $this->category->delete($idCategory);

        $this->queryContainer->getConnection()->commit();
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function runDeletePlugins($idCategory)
    {
        foreach ($this->deletePlugins as $deletePlugin) {
            $deletePlugin->delete($idCategory);
        }
    }

}
