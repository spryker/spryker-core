<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Foo;

use Generated\Shared\Transfer\CategoryLocalizedTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface;
use Spryker\Zed\Category\Business\Tree\NodeWriterInterface;
use Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;

class CategoryManager
{

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\Category\Business\Tree\NodeWriterInterface
     */
    protected $nodeWriter;

    /**
     * @var \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface
     */
    protected $closureTableWriter;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    protected $localeFacade;


    public function __construct(
        ProductCategoryToCategoryInterface $categoryFacade,
        CategoryToLocaleInterface $localeFacade,
        CategoryQueryContainerInterface $queryContainer,
        NodeWriterInterface $nodeWriter,
        ClosureTableWriterInterface $closureTableWriter
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->nodeWriter = $nodeWriter;
        $this->closureTableWriter = $closureTableWriter;
        $this->localeFacade = $localeFacade;
        $this->queryContainer = $queryContainer;
    }

    public function create(CategoryLocalizedTransfer $categoryLocalizedTransfer)
    {
        $categoryLocalizedTransfer = $this->persistCategory($categoryLocalizedTransfer);
/*        $nodeTransfer = $this->persistNode($categoryLocalizedTransfer);
        $urlTransfer = $this->persistUrl($categoryLocalizedTransfer, $nodeTransfer, $localeTransfer);

        $this->touchNavigationActive();
        $this->touchCategoryActiveRecursive($nodeTransfer);*/

        return $categoryLocalizedTransfer;
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedTransfer $CategoryLocalizedTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function persistNode(CategoryLocalizedTransfer $CategoryLocalizedTransfer)
    {
        $nodeTransfer = (new NodeTransfer())
            ->setFkCategory($CategoryLocalizedTransfer->requireIdCategory()
            ->getIdCategory())
            ->setIsMain(true);

        $idNode = $this->persistNodeEntity($nodeTransfer, $CategoryLocalizedTransfer);

        $nodeTransfer->setIdCategoryNode($idNode);

        return $nodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedTransfer $CategoryLocalizedTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedTransfer
     */
    protected function persistCategory(CategoryLocalizedTransfer $CategoryLocalizedTransfer)
    {
        $CategoryLocalizedTransfer->setIsActive(true);
        $CategoryLocalizedTransfer->setIsInMenu(true);
        $CategoryLocalizedTransfer->setIsClickable(true);

        $idCategory = $this->persistCategoryEntity($CategoryLocalizedTransfer);
        $CategoryLocalizedTransfer->setIdCategory($idCategory);

        return $CategoryLocalizedTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedTransfer $CategoryLocalizedTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function persistUrl(
        CategoryLocalizedTransfer $CategoryLocalizedTransfer,
        NodeTransfer $nodeTransfer,
        LocaleTransfer $localeTransfer
    ) {
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

    /**
     * @param CategoryLocalizedTransfer $categoryLocalizedTransfer
     *
     * @return int
     */
    protected function persistCategoryEntity(CategoryLocalizedTransfer $categoryLocalizedTransfer)
    {
        $idCategory = null;
        $localeTransfer = $categoryLocalizedTransfer->requireLocale()->getLocale();

        $data = $categoryLocalizedTransfer->toArray();
        unset($data['locale']);
        
        $categoryTransfer = (new CategoryTransfer())->fromArray(
            $data
        );

        $category = $this->queryContainer
            ->queryCategoryByKey($categoryLocalizedTransfer->requireCategoryKey()->getCategoryKey())
            ->findOne();

        if ($category) {
            $attributesQuery = $this->queryContainer
                ->queryAttributeByCategoryIdAndLocale($category->getIdCategory(), $localeTransfer->getIdLocale())
                ->find();

            if (!$attributesQuery) {
                $idCategory = $this->categoryFacade->createCategory($categoryTransfer, $localeTransfer);
            }
        }
        else {
            $idCategory = $this->categoryFacade->createCategory($categoryTransfer, $localeTransfer);
        }

        return $idCategory;
    }

    protected function persistNodeEntity(
        NodeTransfer $nodeTransfer,
        CategoryLocalizedTransfer $categoryLocalizedTransfer
    ) {
        $this->queryContainer->getConnection()->beginTransaction();

        $idNode = $this->nodeWriter->create($nodeTransfer);
        $nodeTransfer->setIdCategoryNode($idNode);

        $this->closureTableWriter->create($nodeTransfer);

        $this->queryContainer->getConnection()->commit();

        return $idNode;
    }

    /**
     * @return void
     */
    protected function touchNavigationActive()
    {

    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    protected function touchCategoryActiveRecursive(NodeTransfer $categoryNode)
    {

    }

}
