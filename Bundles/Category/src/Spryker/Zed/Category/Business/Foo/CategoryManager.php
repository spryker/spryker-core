<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Foo;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryManager
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     */
    public function __construct(CategoryQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    public function create(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
    {
        $nodeTransfer = $this->persistNode($categoryTransfer);

        $categoryTransfer = $this->persistAttributes($categoryTransfer, $localeTransfer);

        $urlTransfer = $this->persistUrl($categoryTransfer, $nodeTransfer, $localeTransfer);

        return $categoryTransfer;
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    /**
     * @param CategoryTransfer $categoryTransfer
     *
     * @return NodeTransfer
     */
    protected function persistNode(CategoryTransfer $categoryTransfer)
    {
        $nodeTransfer = new NodeTransfer();
        return $nodeTransfer;
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return CategoryTransfer
     */
    protected function persistAttributes(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
    {
        return $categoryTransfer;
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return UrlTransfer
     */
    protected function persistUrl(CategoryTransfer $categoryTransfer, NodeTransfer $nodeTransfer, LocaleTransfer $localeTransfer)
    {
        $urlTransfer = new UrlTransfer();
        return $urlTransfer;
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getCategoryEntity($idCategory)
    {
        return $this->queryContainer->queryCategoryById($idCategory)->findOne();
    }

    protected function persistCategoryEntity(CategoryTransfer $categoryTransfer)
    {
        $this->connection->beginTransaction();

        $categoryTransfer->setIsActive(true);
        $categoryTransfer->setIsInMenu(true);
        $categoryTransfer->setIsClickable(true);

        $idCategory = $this->categoryFacade->createCategory($categoryTransfer, $localeTransfer);

        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setIsMain(true);

        $this->categoryFacade->createCategoryNode($categoryNodeTransfer, $localeTransfer);

        $this->connection->commit();

        return $idCategory;
    }
}
