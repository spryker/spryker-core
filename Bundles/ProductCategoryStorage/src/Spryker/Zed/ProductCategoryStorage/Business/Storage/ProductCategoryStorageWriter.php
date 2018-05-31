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
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface;

class ProductCategoryStorageWriter implements ProductCategoryStorageWriterInterface
{
    const ID_CATEGORY_NODE = 'id_category_node';
    const FK_CATEGORY = 'fk_category';
    const NAME = 'name';

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
        $categories = [];
        foreach ($spyProductAbstractLocalizedEntities as $productAbstractLocalizedEntity) {
            $localizedCategory = $this->generateCategories($productAbstractLocalizedEntity->getFkProductAbstract(), $productAbstractLocalizedEntity->getFkLocale());
            $categories[$productAbstractLocalizedEntity->getFkProductAbstract()][$productAbstractLocalizedEntity->getFkLocale()] = $localizedCategory;
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

        $productCategoryMappings = $this->queryContainer->queryProductCategoryMappings($idProductAbstract)->find();

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
            $queryPath = $this->queryContainer->queryPath($node->getIdCategoryNode(), $idLocale);
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
        $urlQuery = $this->queryContainer
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
}
