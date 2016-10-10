<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Category\Communication\Form\CategoryType;
use Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryEditDataProvider
{

    const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface $localeFacade
     */
    public function __construct(CategoryQueryContainerInterface $queryContainer, CategoryToLocaleInterface $localeFacade)
    {
        $this->queryContainer = $queryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getData()
    {
        $categoryEntity = $this->findCategory();
        $categoryTransfer = $this->buildCategoryTransfer($categoryEntity);

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function buildCategoryTransfer(SpyCategory $categoryEntity)
    {
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->fromArray($categoryEntity->toArray(), true);
        $categoryTransfer = $this->addCategoryNodeTransfer($categoryEntity, $categoryTransfer);
        $categoryTransfer = $this->addParentCategoryNodeTransfer($categoryEntity, $categoryTransfer);
        $categoryTransfer = $this->addLocalizedAttributesTransferCollection($categoryEntity, $categoryTransfer);
        $categoryTransfer = $this->addExtraParentTransferCollection($categoryTransfer);

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function addCategoryNodeTransfer(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer)
    {
        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setIdCategoryNode($categoryEntity->getVirtualColumn('id_category_node'));
        $categoryNodeTransfer->setIsMain($categoryEntity->getVirtualColumn('is_main'));
        $categoryNodeTransfer->setIsRoot($categoryEntity->getVirtualColumn('is_root'));
        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function addParentCategoryNodeTransfer(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer)
    {
        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setIdCategoryNode($categoryEntity->getVirtualColumn('fk_parent_category_node'));
        $categoryNodeTransfer->setIsMain($categoryEntity->getVirtualColumn('is_main'));
        $categoryNodeTransfer->setIsRoot($categoryEntity->getVirtualColumn('is_root'));
        $categoryTransfer->setParentCategoryNode($categoryNodeTransfer);

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function addLocalizedAttributesTransferCollection(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer)
    {
        $categoryTransfer->fromArray($categoryEntity->toArray(), true);
        $categoryLocalizedAttributesEntityCollection = $categoryEntity->getAttributes();

        foreach ($categoryLocalizedAttributesEntityCollection as $categoryLocalizedAttributesEntity) {
            $categoryLocalizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
            $categoryLocalizedAttributesTransfer->fromArray($categoryLocalizedAttributesEntity->toArray(), true);
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->fromArray($categoryLocalizedAttributesEntity->getLocale()->toArray(), true);
            $categoryLocalizedAttributesTransfer->setLocale($localeTransfer);
            $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributesTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function addExtraParentTransferCollection(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeTransfer = $categoryTransfer->getCategoryNode();
        $nodeEntityList = $this->queryContainer->queryNotMainNodesByCategoryId($categoryTransfer->getIdCategory())
            ->where(
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE . ' != ?',
                $categoryNodeTransfer->getIdCategoryNode()
            )
            ->find();

        foreach ($nodeEntityList as $nodeEntity) {
            $categoryNodeTransfer = new NodeTransfer();
            $categoryNodeTransfer->fromArray($nodeEntity->toArray(), true);
            $categoryTransfer->addExtraParent($categoryNodeTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $parentCategories = $this->getCategoriesWithPaths();

        return [
            static::DATA_CLASS => CategoryTransfer::class,
            CategoryType::OPTION_PARENT_CATEGORY_NODE_CHOICES => $parentCategories,
        ];
    }

    /**
     * @return array
     */
    protected function getCategoriesWithPaths()
    {
        $idLocale = $this->getIdLocale();
        $categoryEntityList = $this->queryContainer->queryCategory($idLocale)->find();

        $categoryNodes = [];

        foreach ($categoryEntityList as $categoryEntity) {
            foreach ($categoryEntity->getNodes() as $nodeEntity) {
                $path = $this->buildPath($nodeEntity);
                $categoryName = $categoryEntity->getLocalisedAttributes($idLocale)->getFirst()->getName();

                $categoryNodeTransfer = new NodeTransfer();
                $categoryNodeTransfer->setPath($path);
                $categoryNodeTransfer->setIdCategoryNode($nodeEntity->getIdCategoryNode());
                $categoryNodeTransfer->setName($categoryName);

                $categoryNodes[] = $categoryNodeTransfer;
            }
        }

        return $categoryNodes;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return string
     */
    protected function buildPath(SpyCategoryNode $categoryNodeEntity)
    {
        $idLocale = $this->getIdLocale();
        $idCategoryNode = $categoryNodeEntity->getIdCategoryNode();
        $pathTokens = $this->queryContainer->queryPath($idCategoryNode, $idLocale, false, true)
            ->clearSelectColumns()->addSelectColumn('name')
            ->find();

        return '/' . implode('/', $pathTokens);
    }

    /**
     * @return int
     */
    protected function getIdLocale()
    {
        return $this->localeFacade->getCurrentLocale()->getIdLocale();
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function findCategory()
    {
        $categoryEntity = $this->queryContainer
            ->queryCategoryById($this->getIdCategory())
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, 'fk_category')
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, 'fk_parent_category_node')
            ->withColumn(SpyCategoryNodeTableMap::COL_IS_MAIN, 'is_main')
            ->withColumn(SpyCategoryNodeTableMap::COL_IS_ROOT, 'is_root')
            ->findOne();

        return $categoryEntity;
    }

    /**
     * @return int
     */
    protected function getIdCategory()
    {
        return Request::createFromGlobals()->query->getInt('id-category');
    }

}
