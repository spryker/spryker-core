<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener;

use ArrayObject;
use Everon\Component\Collection\Collection;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\ProductCategoryStorageTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryStorage\Communication\ProductCategoryStorageCommunicationFactory getFactory()
 */
class AbstractProductCategoryStorageListener extends AbstractPlugin
{
    const ID_CATEGORY_NODE = 'id_category_node';
    const FK_CATEGORY = 'fk_category';
    const NAME = 'name';

    /**
     * @var \Everon\Component\Collection\CollectionInterface
     */
    protected $categoryCacheCollection;

    /**
     * AbstractProductCategoryStorageListener constructor.
     */
    public function __construct()
    {
        $this->categoryCacheCollection = new Collection([]);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $spyProductAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $categories = [];
        foreach ($spyProductAbstractLocalizedEntities as $productAbstractLocalizedEntity) {
            $localizedCategory = $this->generateCategories($productAbstractLocalizedEntity->getFkProductAbstract(), $productAbstractLocalizedEntity->getFkLocale());
            $categories[$productAbstractLocalizedEntity->getFkProductAbstract()][$productAbstractLocalizedEntity->getFkLocale()] = $localizedCategory;
        }

        $spyProductAbstractStorageEntities = $this->findProductAbstractCategoryStorageEntitiesByProductAbstractIds($productAbstractIds);
        $this->refreshData($spyProductAbstractLocalizedEntities, $spyProductAbstractStorageEntities, $categories);
    }

    /**
     * @param array $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractStorageEntities
     * @param array $categories
     *
     * @return void
     */
    protected function refreshData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractStorageEntities, array $categories)
    {
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity->getFkProductAbstract();
            $localeName = $spyProductAbstractLocalizedEntity->getLocale()->getLocaleName();
            if (isset($spyProductAbstractStorageEntities[$idProduct][$localeName])) {
                $this->refreshDataSet($spyProductAbstractLocalizedEntity, $categories, $spyProductAbstractStorageEntities[$idProduct][$localeName]);
            } else {
                $this->refreshDataSet($spyProductAbstractLocalizedEntity, $categories);
            }
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $categories
     * @param \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage|null $spyProductAbstractCategoryStorageEntity
     *
     * @return void
     */
    protected function refreshDataSet(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $categories, ?SpyProductAbstractCategoryStorage $spyProductAbstractCategoryStorageEntity = null)
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
        $spyProductAbstractCategoryStorageEntity->setStore($this->getStoreName());
        $spyProductAbstractCategoryStorageEntity->setLocale($spyProductAbstractLocalizedEntity->getLocale()->getLocaleName());
        $spyProductAbstractCategoryStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param \ArrayObject $categories
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer
     */
    protected function getProductAbstractCategoryTransfer(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, ArrayObject $categories)
    {
        $productAbstractCategoryStorageTransfer = new ProductAbstractCategoryStorageTransfer();
        $productAbstractCategoryStorageTransfer->setCategories($categories);
        $productAbstractCategoryStorageTransfer->setIdProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());

        return $productAbstractCategoryStorageTransfer;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    protected function generateCategories($idProductAbstract, $idLocale)
    {
        $key = sprintf('%d_%d', $idProductAbstract, $idLocale);
        if ($this->categoryCacheCollection->has($key)) {
            return $this->categoryCacheCollection->get($key);
        }

        $productCategoryMappings = $this->getQueryContainer()->queryProductCategoryMappings($idProductAbstract)->find();

        $categories = new ArrayObject();
        foreach ($productCategoryMappings as $mapping) {
            $categories = $this->generateProductCategoryData($mapping, $categories, $idLocale);
        }

        $this->categoryCacheCollection->set($key, $categories);

        return $categories;
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategory
     * @param \ArrayObject $productCategoryCollection
     * @param int $idLocale
     *
     * @return array
     */
    protected function generateProductCategoryData(SpyProductCategory $productCategory, $productCategoryCollection, $idLocale)
    {
        foreach ($productCategory->getSpyCategory()->getNodes() as $node) {
            $queryPath = $this->getQueryContainer()->queryPath($node->getIdCategoryNode(), $idLocale);
            $pathTokens = $queryPath->find();

            $productCategoryCollection = $this->generateCategoryData($pathTokens, $productCategoryCollection, $idLocale);
        }

        return $productCategoryCollection;
    }

    /**
     * @param array $pathTokens
     * @param \ArrayObject $productCategoryCollection
     * @param int $idLocale
     *
     * @return array
     */
    protected function generateCategoryData(array $pathTokens, $productCategoryCollection, $idLocale)
    {
        foreach ($pathTokens as $pathItem) {
            $idNode = (int)$pathItem[self::ID_CATEGORY_NODE];
            $idCategory = (int)$pathItem[self::FK_CATEGORY];
            $url = $this->generateUrl($idNode, $idLocale);

            $productCategoryCollection[] = (new ProductCategoryStorageTransfer())
                ->setCategoryNodeId($idNode)
                ->setCategoryId($idCategory)
                ->setUrl($url)
                ->setName($pathItem[self::NAME]);
        }

        return $productCategoryCollection;
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     *
     * @return null|string
     */
    protected function generateUrl($idNode, $idLocale)
    {
        $urlQuery = $this->getQueryContainer()
            ->queryUrlByIdCategoryNode($idNode, $idLocale);

        $url = $urlQuery->findOne();
        return ($url ? $url->getUrl() : null);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractCategoryStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractCategoryStorageEntities = $this->getQueryContainer()->queryProductAbstractCategoryStorageByIds($productAbstractIds)->find();
        $productAbstractStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractCategoryStorageEntities as $productAbstractCategoryStorageEntity) {
            $productAbstractStorageEntitiesByIdAndLocale[$productAbstractCategoryStorageEntity->getFkProductAbstract()][$productAbstractCategoryStorageEntity->getLocale()] = $productAbstractCategoryStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdAndLocale;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    protected function getRelatedCategoryIds(array $categoryIds)
    {
        $relatedCategoryIds = [];
        foreach ($categoryIds as $categoryId) {
            $categoryNodes = $this->getFactory()->getCategoryFacade()->getAllNodesByIdCategory($categoryId);
            foreach ($categoryNodes as $categoryNode) {
                $result = $this->getQueryContainer()->queryAllCategoryIdsByNodeId($categoryNode->getIdCategoryNode())->find()->getData();
                $relatedCategoryIds = array_merge($relatedCategoryIds, $result);
            }
        }

        return array_unique($relatedCategoryIds);
    }
}
