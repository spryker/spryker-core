<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Business\Model\Category\CategoryInterface;
use Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttributeInterface;
use Spryker\Zed\Category\Business\Model\CategoryExtraParents\CategoryExtraParentsInterface;
use Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeInterface;
use Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventInterface;
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
     * @var \Spryker\Zed\Category\Business\Model\CategoryExtraParents\CategoryExtraParentsInterface
     */
    protected $categoryExtraParents;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface
     */
    protected $categoryUrl;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var array|\Spryker\Zed\Category\Dependency\Plugin\CategoryRelationDeletePluginInterface
     */
    protected $deletePlugins;

    /**
     * @var \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationUpdatePluginInterface[]
     */
    protected $updatePlugins;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Category\Business\Model\Category\CategoryInterface $category
     * @param \Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeInterface $categoryNode
     * @param \Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttributeInterface $categoryAttribute
     * @param \Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface $categoryUrl
     * @param \Spryker\Zed\Category\Business\Model\CategoryExtraParents\CategoryExtraParentsInterface $categoryExtraParents
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationDeletePluginInterface[] $deletePlugins
     * @param \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationUpdatePluginInterface[] $updatePlugins
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventInterface|null $eventFacade
     */
    public function __construct(
        CategoryInterface $category,
        CategoryNodeInterface $categoryNode,
        CategoryAttributeInterface $categoryAttribute,
        CategoryUrlInterface $categoryUrl,
        CategoryExtraParentsInterface $categoryExtraParents,
        CategoryQueryContainerInterface $queryContainer,
        array $deletePlugins,
        array $updatePlugins,
        ?CategoryToEventInterface $eventFacade = null
    ) {
        $this->category = $category;
        $this->categoryNode = $categoryNode;
        $this->categoryAttribute = $categoryAttribute;
        $this->categoryUrl = $categoryUrl;
        $this->categoryExtraParents = $categoryExtraParents;
        $this->queryContainer = $queryContainer;
        $this->deletePlugins = $deletePlugins;
        $this->updatePlugins = $updatePlugins;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory)
    {
        $categoryTransfer = new CategoryTransfer();

        $categoryTransfer = $this->category->read($idCategory, $categoryTransfer);
        $categoryTransfer = $this->categoryNode->read($idCategory, $categoryTransfer);
        $categoryTransfer = $this->categoryAttribute->read($idCategory, $categoryTransfer);
        $categoryTransfer = $this->categoryExtraParents->read($idCategory, $categoryTransfer);

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $this->triggerEvent(CategoryEvents::CATEGORY_BEFORE_CREATE, $categoryTransfer);

        $this->category->create($categoryTransfer);
        $this->categoryNode->create($categoryTransfer);
        $this->categoryAttribute->create($categoryTransfer);
        $this->categoryUrl->create($categoryTransfer);
        $this->categoryExtraParents->create($categoryTransfer);
        $this->runUpdatePlugins($categoryTransfer);

        $this->triggerEvent(CategoryEvents::CATEGORY_AFTER_CREATE, $categoryTransfer);

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

        $this->triggerEvent(CategoryEvents::CATEGORY_BEFORE_UPDATE, $categoryTransfer);

        $this->runUpdatePlugins($categoryTransfer);

        $this->category->update($categoryTransfer);
        $this->categoryNode->update($categoryTransfer);
        $this->categoryAttribute->update($categoryTransfer);
        $this->categoryUrl->update($categoryTransfer);
        $this->categoryExtraParents->update($categoryTransfer);

        $this->triggerEvent(CategoryEvents::CATEGORY_AFTER_UPDATE, $categoryTransfer);

        $this->queryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function runUpdatePlugins(CategoryTransfer $categoryTransfer)
    {
        foreach ($this->updatePlugins as $updatePlugin) {
            $updatePlugin->update($categoryTransfer);
        }
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $categoryTransfer = $this->createCategoryTransfer($idCategory);

        $this->triggerEvent(CategoryEvents::CATEGORY_BEFORE_DELETE, $categoryTransfer);

        $this->categoryAttribute->delete($idCategory);
        $this->categoryUrl->delete($idCategory);
        $this->categoryNode->delete($idCategory);
        $this->categoryExtraParents->delete($idCategory);

        $this->runDeletePlugins($idCategory);

        $this->category->delete($idCategory);

        $this->triggerEvent(CategoryEvents::CATEGORY_AFTER_DELETE, $categoryTransfer);

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

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function triggerEvent($eventName, CategoryTransfer $categoryTransfer)
    {
        if ($this->eventFacade === null) {
            return;
        }

        $this->eventFacade->trigger($eventName, $categoryTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function createCategoryTransfer($idCategory)
    {
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setIdCategory($idCategory);

        return $categoryTransfer;
    }
}
