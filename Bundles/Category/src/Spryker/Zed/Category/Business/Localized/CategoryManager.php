<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Localized;

use Generated\Shared\Transfer\CategoryLocalizedTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Category\Business\Manager\NodeUrlManagerInterface;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface;
use Spryker\Zed\Category\Business\Tree\NodeWriterInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

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
     * @var \Spryker\Zed\Category\Business\Manager\NodeUrlManagerInterface
     */
    protected $nodeUrlManager;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Url\Business\UrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacadeInterface
     */
    protected $touchFacade;


    public function __construct(
        $categoryFacade,
        $localeFacade,
        CategoryQueryContainerInterface $queryContainer,
        NodeWriterInterface $nodeWriter,
        ClosureTableWriterInterface $closureTableWriter,
        NodeUrlManagerInterface $nodeUrlManager,
        $urlFacade,
        $touchFacade
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->nodeWriter = $nodeWriter;
        $this->closureTableWriter = $closureTableWriter;
        $this->localeFacade = $localeFacade;
        $this->queryContainer = $queryContainer;
        $this->nodeUrlManager = $nodeUrlManager;
        $this->urlFacade = $urlFacade;
        $this->touchFacade = $touchFacade;
    }

    public function create(CategoryLocalizedTransfer $categoryLocalizedTransfer, NodeTransfer $nodeTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $entity = $this->queryContainer->queryCategoryByKey($categoryLocalizedTransfer->requireCategoryKey()->getCategoryKey());
        if ($entity) {
            throw new \Exception(sprintf(
                'Category with key "%s" already exists',
                $categoryLocalizedTransfer->getCategoryKey()
            ));
        }

        $categoryLocalizedTransfer = $this->persistCategory($categoryLocalizedTransfer);
        $nodeTransfer = $this->persistNode($categoryLocalizedTransfer, $nodeTransfer);
        $this->persistUrl($categoryLocalizedTransfer, $nodeTransfer);

        $this->touchNavigationActive();
        $this->touchCategoryActiveRecursive($nodeTransfer);

        $this->queryContainer->getConnection()->commit();

        return $categoryLocalizedTransfer->getIdCategory();
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedTransfer $categoryLocalizedTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedTransfer
     */
    protected function persistCategory(CategoryLocalizedTransfer $categoryLocalizedTransfer)
    {
        $categoryTransfer = $this->persistCategoryEntity($categoryLocalizedTransfer);
        $categoryLocalizedTransfer->setIdCategory($categoryTransfer->getIdCategory());

        return $categoryLocalizedTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedTransfer $categoryLocalizedTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function persistNode(CategoryLocalizedTransfer $categoryLocalizedTransfer, NodeTransfer $nodeTransfer)
    {
        $idCategory = $categoryLocalizedTransfer->requireIdCategory()->getIdCategory();

        $nodeTransfer
            ->setFkCategory($idCategory)
            ->setIsMain(true);

        $nodeTransfer = $this->persistNodeEntity($nodeTransfer);

        return $nodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedTransfer $CategoryLocalizedTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    protected function persistUrl(CategoryLocalizedTransfer $CategoryLocalizedTransfer, NodeTransfer $nodeTransfer)
    {
        $localeTransfer = $CategoryLocalizedTransfer->requireLocale()->getLocale();

        $idCategoryNode = $nodeTransfer->requireIdCategoryNode()->getIdCategoryNode();

        if (!$this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, $localeTransfer)) {
            $this->nodeUrlManager->createUrl(
                $nodeTransfer,
                $localeTransfer
            );
        }
        else {
            $this->nodeUrlManager->updateUrl(
                $nodeTransfer,
                $localeTransfer
            );
        }
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getCategoryEntity($idCategory)
    {
        return $this->queryContainer->queryCategoryById($idCategory)
            ->findOne();
    }

    /**
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getNodeEntity($idNode)
    {
        return $this->queryContainer->queryCategoryNodeByNodeId($idNode)
            ->findOne();
    }

    /**
     * @param CategoryLocalizedTransfer $categoryLocalizedTransfer
     *
     * @return CategoryLocalizedTransfer
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

        $categoryEntity = $this->queryContainer
            ->queryCategoryById($categoryLocalizedTransfer->getIdCategory())
            ->findOne();

        if (!$categoryEntity) {
            $idCategory = $this->categoryFacade->createCategory($categoryTransfer, $localeTransfer);
            $categoryTransfer->setIdCategory($idCategory);
        }
        else {
            $idCategory = $categoryEntity->getIdCategory();
            $categoryTransfer->setIdCategory($idCategory);
            $this->persistCategoryAttribute($categoryTransfer, $localeTransfer);
        }

        return $categoryTransfer;
    }

    protected function persistNodeEntity(NodeTransfer $nodeTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $idNode = $this->nodeWriter->create($nodeTransfer);
        $nodeTransfer->setIdCategoryNode($idNode);

        $nodeEntity = $this->getNodeEntity($idNode);

        if (!$nodeEntity) {
            $this->closureTableWriter->create($nodeTransfer);
        }
        else {
            $this->closureTableWriter->moveNode($nodeTransfer);
        }

        $this->queryContainer->getConnection()->commit();

        return $nodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function persistCategoryAttribute(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $categoryAttributeEntity = $this->queryContainer
            ->queryAttributeByCategoryId($category->requireIdCategory()->getIdCategory())
            ->filterByFkLocale($locale->requireIdLocale()->getIdLocale())
            ->findOneOrCreate();

        $this->saveCategoryAttribute($category, $locale, $categoryAttributeEntity);
    }


    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     *
     * @return void
     */
    protected function saveCategoryAttribute(
        CategoryTransfer $category,
        LocaleTransfer $locale,
        SpyCategoryAttribute $categoryAttributeEntity
    ) {
        $categoryAttributeEntity->fromArray($category->toArray());
        $categoryAttributeEntity->setFkCategory($category->getIdCategory());
        $categoryAttributeEntity->setFkLocale($locale->getIdLocale());

        $categoryAttributeEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    protected function touchCategoryActiveRecursive(NodeTransfer $categoryNode)
    {
        $closureQuery= new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNode($categoryNode->getIdCategoryNode());

        foreach ($nodes as $node) {
            $this->touchCategoryActive($node->getFkCategoryNodeDescendant());
        }

        $this->touchCategoryActive($categoryNode->getIdCategoryNode());
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    protected function touchCategoryDeletedRecursive(NodeTransfer $categoryNode)
    {
        $closureQuery= new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNode($categoryNode->getIdCategoryNode());

        foreach ($nodes as $node) {
            $this->touchCategoryDeleted($node->getFkCategoryNodeDescendant());
        }

        $this->touchCategoryDeleted($categoryNode->getIdCategoryNode());
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    protected function touchCategoryActive($idCategoryNode)
    {
        $this->touchFacade->touchActive(CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    protected function touchCategoryDeleted($idCategoryNode)
    {
        $this->touchFacade->touchDeleted(CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    /**
     * @return void
     */
    protected function touchNavigationActive()
    {
        $navigationItems = $this->touchFacade->getItemsByType(CategoryConstants::RESOURCE_TYPE_NAVIGATION);

        $itemIds = [];
        foreach ($navigationItems as $touchTransfer) {
            $itemIds[] = $touchTransfer->getItemId();
        }

        $this->touchFacade->bulkTouchSetActive(CategoryConstants::RESOURCE_TYPE_NAVIGATION, $itemIds);
    }

    /**
     * @return void
     */
    protected function touchNavigationUpdated()
    {
        $this->touchNavigationActive();
    }


}
