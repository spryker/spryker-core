<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\ProductCategoryStorageTransfer;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface;

class MultiStoreProductCategoryStorageWriter implements ProductCategoryStorageWriterInterface
{
    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_ID_CATEGORY_NODE
     */
    protected const COL_ID_CATEGORY_NODE = 'id_category_node';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_FK_CATEGORY_NODE_DESCENDANT
     */
    protected const COL_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_FK_CATEGORY
     */
    protected const COL_FK_CATEGORY = 'fk_category';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_NAME
     */
    protected const COL_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_URL
     */
    protected const COL_URL = 'url';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_LOCALE
     */
    protected const COL_LOCALE = 'locale';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_STORE
     */
    protected const COL_STORE = 'store';

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface
     */
    protected $productCategoryStorageRepository;

    /**
     * @var array|null
     */
    protected static $categoryTree;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface $categoryFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository
     */
    public function __construct(
        ProductCategoryStorageToCategoryInterface $categoryFacade,
        ProductCategoryStorageQueryContainerInterface $queryContainer,
        ProductCategoryStorageToStoreFacadeInterface $storeFacade,
        ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->queryContainer = $queryContainer;
        $this->storeFacade = $storeFacade;
        $this->productCategoryStorageRepository = $productCategoryStorageRepository;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $productAbstractIds = [60];

        $productAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $productAbstractCategoryStorageEntities = $this->findProductAbstractCategoryStorageEntitiesByProductAbstractIds($productAbstractIds);

        $productCategories = $this->findProductAbstractCategories($productAbstractIds);
        $localeNameMapByStoreName = $this->getLocaleNameMapByStoreName();

        foreach ($localeNameMapByStoreName as $storeName => $storeLocales) {
            foreach ($storeLocales as $localeName) {
                foreach ($productAbstractLocalizedEntities as $productAbstractLocalizedEntity) {
                    $idProductAbstract = $productAbstractLocalizedEntity->getFkProductAbstract();

                    $productCategoryTransfers = $this->getProductCategoriesFromCategories(
                        $productCategories[$idProductAbstract] ?? [],
                        $storeName,
                        $localeName
                    );

                    $productAbstractCategoryStorageEntity = $productAbstractCategoryStorageEntities[$idProductAbstract][$storeName][$localeName] ?? new SpyProductAbstractCategoryStorage();

                    if (!count($productCategoryTransfers)) {
                        if (!$productAbstractCategoryStorageEntity->isNew()) {
                            $productAbstractCategoryStorageEntity->delete();
                        }

                        continue;
                    }

                    $productAbstractCategoryStorageTransfer = (new ProductAbstractCategoryStorageTransfer())
                        ->setCategories((new ArrayObject($productCategoryTransfers)))
                        ->setIdProductAbstract($idProductAbstract);

                    $productAbstractCategoryStorageEntity
                        ->setFkProductAbstract($idProductAbstract)
                        ->setStore($storeName)
                        ->setLocale($localeName)
                        ->setData($productAbstractCategoryStorageTransfer->toArray());

                    $productAbstractCategoryStorageEntity->save();
                }
            }
        }
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds): void
    {
        $productAbstractCategoryStorageEntities = $this->queryContainer->queryProductAbstractCategoryStorageByIds($productAbstractIds)->find();

        foreach ($productAbstractCategoryStorageEntities as $productAbstractCategoryStorageEntity) {
            $productAbstractCategoryStorageEntity->delete();
        }
    }

    /**
     * @param array $categoryIds
     *
     * @return int[]
     */
    public function getRelatedCategoryIds(array $categoryIds): array
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
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[] $productCategoryEntities
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    protected function getProductCategoriesFromCategories(array $productCategoryEntities, string $storeName, string $localeName): array
    {
        $productCategoryTransfers = [];

        foreach ($productCategoryEntities as $productCategoryEntity) {
            $productCategoryTransfers = array_merge(
                $productCategoryTransfers,
                $this->generateProductCategoryStorageTransfers($productCategoryEntity, $storeName, $localeName)
            );
        }

        return $productCategoryTransfers;
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategory
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    protected function generateProductCategoryStorageTransfers(SpyProductCategory $productCategory, string $storeName, string $localeName): array
    {
        $productCategoryTransfers = [];

        foreach ($productCategory->getSpyCategory()->getNodes() as $node) {
            $pathTokens = [];
            $categoryPaths = $this->loadAllParents($node->getIdCategoryNode());

            foreach ($categoryPaths as $idCategoryNode => $categoryPath) {
                if ($categoryPath['store'] === $storeName && $categoryPath['locale'] === $localeName) {
                    $pathTokens[] = $categoryPath;
                }
            }

            $productCategoryTransfers = array_merge($productCategoryTransfers, $this->generateCategoryDataTransfers($pathTokens));
        }

        return $productCategoryTransfers;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[]
     */
    protected function findProductAbstractCategories(array $productAbstractIds): array
    {
        $mappedProductCategoryEntities = [];
        $productCategoryEntities = $this->queryContainer->queryProductCategoryWithCategoryNodes($productAbstractIds)->find();

        foreach ($productCategoryEntities as $productCategoryEntity) {
            $mappedProductCategoryEntities[$productCategoryEntity->getFkProductAbstract()][] = $productCategoryEntity;
        }

        return $mappedProductCategoryEntities;
    }

    /**
     * @param array $pathTokens
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    protected function generateCategoryDataTransfers(array $pathTokens): array
    {
        $productCategoryTransfers = [];

        foreach ($pathTokens as $pathItem) {
            $productCategoryTransfers[] = (new ProductCategoryStorageTransfer())
                ->setCategoryNodeId((int)$pathItem[static::ID_CATEGORY_NODE])
                ->setCategoryId((int)$pathItem[static::FK_CATEGORY])
                ->setUrl($pathItem[static::URL])
                ->setName($pathItem[static::NAME]);
        }

        return $productCategoryTransfers;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes[]
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage[][][]
     */
    protected function findProductAbstractCategoryStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractStorageEntities = [];
        $productAbstractCategoryStorageEntities = $this->queryContainer->queryProductAbstractCategoryStorageByIds($productAbstractIds)->find();

        foreach ($productAbstractCategoryStorageEntities as $productAbstractCategoryStorageEntity) {
            $idProductAbstract = $productAbstractCategoryStorageEntity->getFkProductAbstract();
            $locale = $productAbstractCategoryStorageEntity->getLocale();
            $store = $productAbstractCategoryStorageEntity->getStore();

            $productAbstractStorageEntities[$idProductAbstract][$store][$locale] = $productAbstractCategoryStorageEntity;
        }

        return $productAbstractStorageEntities;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return array
     */
    protected function loadAllParents(int $idCategoryNode): array
    {
        if (static::$categoryTree === null) {
            $this->loadCategoryTree();
        }

        return static::$categoryTree[$idCategoryNode] ?? [];
    }

    /**
     * @return void
     */
    protected function loadCategoryTree(): void
    {
        static::$categoryTree = [];

        $categories = $this->productCategoryStorageRepository->getAllCategoriesOrderedByDescendant();
        $formattedCategories = $this->formatCategories($categories);

        $categoryNodeIds = $this->productCategoryStorageRepository->getAllCategoryNodeIds();

        foreach ($categoryNodeIds as $idCategoryNode) {
            $pathData = [];

            if (isset($formattedCategories[$idCategoryNode])) {
                $pathData = $formattedCategories[$idCategoryNode];
            }

            static::$categoryTree[$idCategoryNode] = [];

            foreach ($pathData as $path) {
                if (!in_array((int)$path[static::COL_ID_CATEGORY_NODE], static::$categoryTree[$idCategoryNode])) {
                    static::$categoryTree[$idCategoryNode][] = $path;
                }
            }
        }
    }

    /**
     * @param array $categories
     *
     * @return array
     */
    protected function formatCategories(array $categories): array
    {
        $formattedCategories = [];

        foreach ($categories as $category) {
            $formattedCategories[$category['fk_category_node_descendant']][] = $category;
        }

        return $formattedCategories;
    }

    /**
     * @return string[][]
     */
    protected function getLocaleNameMapByStoreName(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $localeNameMapByStoreName = [];

        foreach ($storeTransfers as $storeTransfer) {
            $localeNameMapByStoreName[$storeTransfer->getName()] = $storeTransfer->getAvailableLocaleIsoCodes();
        }

        return $localeNameMapByStoreName;
    }
}
