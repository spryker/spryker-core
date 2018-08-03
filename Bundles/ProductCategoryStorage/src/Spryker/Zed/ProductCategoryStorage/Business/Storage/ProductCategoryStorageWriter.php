<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Storage;

use ArrayObject;
use Everon\Component\Collection\Collection;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\ProductCategoryStorageTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class ProductCategoryStorageWriter implements ProductCategoryStorageWriterInterface
{
    const ID_CATEGORY_NODE = 'id_category_node';
    const FK_CATEGORY = 'fk_category';
    const NAME = 'name';
    const URL = 'url';

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var \Everon\Component\Collection\CollectionInterface
     */
    protected $categoryCacheCollection;

    /**
     * @var array
     */
    protected static $categoryTree;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface $categoryFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductCategoryStorageToCategoryInterface $categoryFacade,
        ProductCategoryStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
        $this->categoryCacheCollection = new Collection([]);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $spyProductAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $productCategories = $this->findProductAbstractCategories($productAbstractIds);
        $categories = [];

        foreach ($spyProductAbstractLocalizedEntities as $productAbstractLocalizedEntity) {
            if (isset($productCategories[$productAbstractLocalizedEntity->getFkProductAbstract()])) {
                $mappings = $productCategories[$productAbstractLocalizedEntity->getFkProductAbstract()];
                $localizedCategories = [];
                foreach ($mappings as $mapping) {
                    $localizedCategories = array_merge($localizedCategories, $this->generateProductCategoryLocalizedData($mapping, $productAbstractLocalizedEntity->getFkLocale()));
                }

                $categories[$productAbstractLocalizedEntity->getFkProductAbstract()][$productAbstractLocalizedEntity->getFkLocale()] = $localizedCategories;
            }
        }

        $spyProductAbstractStorageEntities = $this->findProductAbstractCategoryStorageEntitiesByProductAbstractIds($productAbstractIds);
        $this->storeData($spyProductAbstractLocalizedEntities, $spyProductAbstractStorageEntities, $categories);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductAbstractStorageEntities = $this->findProductAbstractCategoryStorageEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($spyProductAbstractStorageEntities as $spyProductAbstractStorageLocalizedEntities) {
            foreach ($spyProductAbstractStorageLocalizedEntities as $spyProductAbstractStorageLocalizedEntity) {
                $spyProductAbstractStorageLocalizedEntity->delete();
            }
        }
    }

    /**
     * @param array $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractStorageEntities
     * @param array $categories
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractStorageEntities, array $categories)
    {
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity->getFkProductAbstract();
            $localeName = $spyProductAbstractLocalizedEntity->getLocale()->getLocaleName();
            if (isset($spyProductAbstractStorageEntities[$idProduct][$localeName])) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $categories, $spyProductAbstractStorageEntities[$idProduct][$localeName]);

                continue;
            }

            $this->storeDataSet($spyProductAbstractLocalizedEntity, $categories);
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $categories
     * @param \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage|null $spyProductAbstractCategoryStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $categories, ?SpyProductAbstractCategoryStorage $spyProductAbstractCategoryStorageEntity = null)
    {
        if ($spyProductAbstractCategoryStorageEntity === null) {
            $spyProductAbstractCategoryStorageEntity = new SpyProductAbstractCategoryStorage();
        }

        $categories = $categories[$spyProductAbstractLocalizedEntity->getFkProductAbstract()][$spyProductAbstractLocalizedEntity->getFkLocale()];
        if (empty($categories)) {
            if (!$spyProductAbstractCategoryStorageEntity->isNew()) {
                $spyProductAbstractCategoryStorageEntity->delete();
            }

            return;
        }

        $productAbstractCategoryStorageTransfer = $this->getProductAbstractCategoryTransfer($spyProductAbstractLocalizedEntity, $categories);
        $spyProductAbstractCategoryStorageEntity->setFkProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $spyProductAbstractCategoryStorageEntity->setData($productAbstractCategoryStorageTransfer->toArray());
        $spyProductAbstractCategoryStorageEntity->setLocale($spyProductAbstractLocalizedEntity->getLocale()->getLocaleName());
        $spyProductAbstractCategoryStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductAbstractCategoryStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $categories
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer
     */
    protected function getProductAbstractCategoryTransfer(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $categories)
    {
        $productAbstractCategoryStorageTransfer = new ProductAbstractCategoryStorageTransfer();
        $productAbstractCategoryStorageTransfer->setCategories((new ArrayObject($categories)));
        $productAbstractCategoryStorageTransfer->setIdProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());

        return $productAbstractCategoryStorageTransfer;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractCategories(array $productAbstractIds)
    {
        $query = SpyProductCategoryQuery::create()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->innerJoinSpyCategory()
            ->addAnd(
                SpyCategoryTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
            ->joinWithSpyCategory()
            ->joinWith('SpyCategory.Node')
            ->orderByProductOrder();

        $productCategories = $query->find();

        $productCategoryMappings = [];
        foreach ($productCategories as $mapping) {
            $productCategoryMappings[$mapping->getFkProductAbstract()][] = $mapping;
        }

        return $productCategoryMappings;
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategory
     * @param int $idLocale
     *
     * @return array
     */
    protected function generateProductCategoryLocalizedData(SpyProductCategory $productCategory, $idLocale)
    {
        $productCategoryCollection = [];
        foreach ($productCategory->getSpyCategory()->getNodes() as $node) {
            $pathTokens = [];
            $categoryPaths = $this->loadAllParents($node->getIdCategoryNode());
            foreach ($categoryPaths as $idCategoryNode => $categoryPath) {
                if ($categoryPath['fk_locale'] === $idLocale) {
                    $pathTokens[] = $categoryPath;
                }
            }

            $productCategoryCollection = array_merge($productCategoryCollection, $this->generateCategoryDataTransfers($pathTokens));
        }

        return $productCategoryCollection;
    }

    /**
     * @param array $pathTokens
     *
     * @return array
     */
    protected function generateCategoryDataTransfers(array $pathTokens)
    {
        $productCategoryCollection = [];
        foreach ($pathTokens as $pathItem) {
            $idNode = (int)$pathItem[self::ID_CATEGORY_NODE];
            $idCategory = (int)$pathItem[self::FK_CATEGORY];

            $productCategoryCollection[] = (new ProductCategoryStorageTransfer())
                ->setCategoryNodeId($idNode)
                ->setCategoryId($idCategory)
                ->setUrl($pathItem[self::URL])
                ->setName($pathItem[self::NAME]);
        }

        return $productCategoryCollection;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractCategoryStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractCategoryStorageEntities = $this->queryContainer->queryProductAbstractCategoryStorageByIds($productAbstractIds)->find();
        $productAbstractStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractCategoryStorageEntities as $productAbstractCategoryStorageEntity) {
            $productAbstractStorageEntitiesByIdAndLocale[$productAbstractCategoryStorageEntity->getFkProductAbstract()][$productAbstractCategoryStorageEntity->getLocale()] = $productAbstractCategoryStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdAndLocale;
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    public function getRelatedCategoryIds(array $categoryIds)
    {
        $relatedCategoryIds = [];
        foreach ($categoryIds as $categoryId) {
            $categoryNodes = $this->categoryFacade->getAllNodesByIdCategory($categoryId);
            foreach ($categoryNodes as $categoryNode) {
                $result = $this->queryContainer->queryAllCategoryIdsByNodeId($categoryNode->getIdCategoryNode())->find()->getData();
                $relatedCategoryIds = array_merge($relatedCategoryIds, $result);
            }
        }

        return array_unique($relatedCategoryIds);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return array
     */
    protected function loadAllParents($idCategoryNode)
    {
        if (static::$categoryTree === null) {
            $this->loadCategoryTree();
        }

        return static::$categoryTree[$idCategoryNode];
    }

    /**
     * @return void
     */
    protected function loadCategoryTree()
    {
        static::$categoryTree = [];

        $nodeQuery = SpyCategoryNodeQuery::create();
        $categoryNodes = $nodeQuery
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            ->find();

        $categoryEntities = $this->loadCategories()->find();
        $formattedCategoriesByLocaleAndNodeIds = $this->formatCategoriesWithLocaleAndNodIds($categoryEntities);

        foreach ($categoryNodes as $categoryNodeEntity) {
            $pathData = [];

            if (isset($formattedCategoriesByLocaleAndNodeIds[$categoryNodeEntity->getIdCategoryNode()])) {
                $pathData = $formattedCategoriesByLocaleAndNodeIds[$categoryNodeEntity->getIdCategoryNode()];
            }

            static::$categoryTree[$categoryNodeEntity->getIdCategoryNode()] = [];

            foreach ($pathData as $path) {
                $idCategoryNode = (int)$path['id_category_node'];
                if (!in_array($idCategoryNode, static::$categoryTree[$categoryNodeEntity->getIdCategoryNode()])) {
                    static::$categoryTree[$categoryNodeEntity->getIdCategoryNode()][] = $path;
                }
            }
        }
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    protected function loadCategories()
    {
        $nodeQuery = SpyCategoryNodeQuery::create();
        $nodeQuery->addJoin(
            SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
            SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE,
            Criteria::LEFT_JOIN
        );

        $nodeQuery->where(SpyUrlTableMap::COL_FK_LOCALE . ' = ' . SpyCategoryAttributeTableMap::COL_FK_LOCALE);

        $nodeQuery
            ->useClosureTableQuery()
                ->orderByFkCategoryNodeDescendant(Criteria::DESC)
                ->orderByDepth(Criteria::DESC)
                ->filterByDepth(null, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
                ->useAttributeQuery()
                ->endUse()
            ->endUse();

        $nodeQuery->filterByIsRoot(0);

        $nodeQuery
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, 'fk_category')
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            ->withColumn(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT, 'fk_category_node_descendant')
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, 'category_key')
            ->withColumn(SpyCategoryAttributeTableMap::COL_FK_LOCALE, 'fk_locale')
            ->withColumn(SpyUrlTableMap::COL_URL, 'url');

        $nodeQuery->setFormatter(new PropelArraySetFormatter());

        return $nodeQuery;
    }

    /**
     * @param array $categoryEntities
     *
     * @return array
     */
    protected function formatCategoriesWithLocaleAndNodIds(array $categoryEntities)
    {
        $categories = [];
        foreach ($categoryEntities as $categoryEntity) {
            $categories[$categoryEntity['fk_category_node_descendant']][] = $categoryEntity;
        }

        return $categories;
    }
}
