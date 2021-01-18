<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductCategoryStorageTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Spryker\Zed\ProductCategoryStorage\Business\Loader\CategoryTreeLoaderInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface;

class ProductCategoryStorageWriter implements ProductCategoryStorageWriterInterface
{
    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_ID_CATEGORY_NODE
     */
    protected const COL_ID_CATEGORY_NODE = 'id_category_node';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_FK_CATEGORY
     */
    protected const COL_FK_CATEGORY = 'fk_category';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_URL
     */
    protected const COL_URL = 'url';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_NAME
     */
    protected const COL_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_LOCALE
     */
    protected const COL_LOCALE = 'locale';

    /**
     * @uses \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository::COL_STORE
     */
    protected const COL_STORE = 'store';

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface
     */
    protected $productCategoryStorageRepository;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface
     */
    protected $productCategoryStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface
     */
    protected $productAbstractReader;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Loader\CategoryTreeLoaderInterface
     */
    protected $categoryTreeLoader;

    /**
     * @var array|null
     */
    protected static $categoryTree;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface $productCategoryStorageEntityManager
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface $productAbstractReader
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Loader\CategoryTreeLoaderInterface $categoryTreeLoader
     */
    public function __construct(
        ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository,
        ProductCategoryStorageEntityManagerInterface $productCategoryStorageEntityManager,
        ProductCategoryStorageToStoreFacadeInterface $storeFacade,
        ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductAbstractReaderInterface $productAbstractReader,
        CategoryTreeLoaderInterface $categoryTreeLoader
    ) {
        $this->productCategoryStorageRepository = $productCategoryStorageRepository;
        $this->productCategoryStorageEntityManager = $productCategoryStorageEntityManager;
        $this->storeFacade = $storeFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productAbstractReader = $productAbstractReader;
        $this->categoryTreeLoader = $categoryTreeLoader;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyCategoryStoreTableMap::COL_FK_CATEGORY
        );

        $productAbstractIds = $this->productAbstractReader->getProductAbstractIdsByCategoryIds($categoryIds);

        $this->publish($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByCategoryStorePublishingEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);
        $productAbstractIds = $this->productAbstractReader->getProductAbstractIdsByCategoryIds($categoryIds);

        $this->publish($productAbstractIds);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $productCategoryTransfers = $this->findProductCategories($productAbstractIds);
        $productAbstractLocalizedAttributesTransfers = $this->productCategoryStorageRepository
            ->getProductAbstractLocalizedAttributes($productAbstractIds);

        $productAbstractCategoryStorageTransfers = $this->productCategoryStorageRepository
            ->getMappedProductAbstractCategoryStorages($productAbstractIds);

        $this->storeData($productAbstractLocalizedAttributesTransfers, $productAbstractCategoryStorageTransfers, $productCategoryTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer[] $productAbstractLocalizedAttributesTransfers
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[][][] $productAbstractCategoryStorageTransfers
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer[][] $productCategoryTransfers
     *
     * @return void
     */
    protected function storeData(
        array $productAbstractLocalizedAttributesTransfers,
        array $productAbstractCategoryStorageTransfers,
        array $productCategoryTransfers
    ): void {
        $localeNameMapByStoreName = $this->getLocaleNameMapByStoreName();

        foreach ($localeNameMapByStoreName as $storeName => $storeLocales) {
            foreach ($storeLocales as $localeName) {
                $this->saveProductAbstractCategoryStorages(
                    $productCategoryTransfers,
                    $productAbstractCategoryStorageTransfers,
                    $productAbstractLocalizedAttributesTransfers,
                    $storeName,
                    $localeName
                );
            }
        }

        $this->removeProductAbstractCategoryStorages($productAbstractCategoryStorageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer[][] $productCategoryTransfers
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[][][] $productAbstractCategoryStorageTransfers
     * @param \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer[] $productAbstractLocalizedAttributesTransfers
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function saveProductAbstractCategoryStorages(
        array $productCategoryTransfers,
        array $productAbstractCategoryStorageTransfers,
        array $productAbstractLocalizedAttributesTransfers,
        string $storeName,
        string $localeName
    ): void {
        foreach ($productAbstractLocalizedAttributesTransfers as $productAbstractLocalizedAttributesTransfer) {
            if ($productAbstractLocalizedAttributesTransfer->getLocale()->getLocaleName() !== $localeName) {
                continue;
            }

            $this->saveProductAbstractCategoryStorage(
                $productCategoryTransfers,
                $productAbstractCategoryStorageTransfers,
                $productAbstractLocalizedAttributesTransfer,
                $storeName,
                $localeName
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer[][] $productCategoryTransfers
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[][][] $productAbstractCategoryStorageTransfers
     * @param \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function saveProductAbstractCategoryStorage(
        array $productCategoryTransfers,
        array $productAbstractCategoryStorageTransfers,
        ProductAbstractLocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer,
        string $storeName,
        string $localeName
    ): void {
        $idProductAbstract = $productAbstractLocalizedAttributesTransfer->getIdProductAbstract();
        $relatedProductCategoryTransfers = $this->getRelatedProductCategoriesFromCategoryTree(
            $productCategoryTransfers[$idProductAbstract] ?? [],
            $storeName,
            $localeName
        );

        $productAbstractCategoryStorageTransfer
            = $productAbstractCategoryStorageTransfers[$idProductAbstract][$storeName][$localeName]
            ?? null;

        if ($productAbstractCategoryStorageTransfer) {
            unset($productAbstractCategoryStorageTransfers[$idProductAbstract][$storeName][$localeName]);
        }

        if (!count($relatedProductCategoryTransfers)) {
            if ($productAbstractCategoryStorageTransfer) {
                $this->productCategoryStorageEntityManager->deleteProductAbstractCategoryStorage(
                    $idProductAbstract,
                    $storeName,
                    $localeName
                );
            }

            return;
        }

        $productAbstractCategoryStorageTransfer = (new ProductAbstractCategoryStorageTransfer())
            ->setCategories((new ArrayObject($relatedProductCategoryTransfers)))
            ->setIdProductAbstract($idProductAbstract);

        $this->productCategoryStorageEntityManager->saveProductAbstractCategoryStorage(
            $idProductAbstract,
            $storeName,
            $localeName,
            $productAbstractCategoryStorageTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[][][]|array[][][] $productAbstractCategoryStorageTransfers
     *
     * @return void
     */
    protected function removeProductAbstractCategoryStorages(array $productAbstractCategoryStorageTransfers): void
    {
        foreach ($productAbstractCategoryStorageTransfers as $idProductAbstract => $relatedToProductTransfers) {
            foreach ($relatedToProductTransfers as $storeName => $relatedToStoreTransfers) {
                $this->removeLocalizedProductAbstractCategoryStorages($relatedToStoreTransfers, $idProductAbstract, $storeName);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[]|array[] $relatedToStoreProductAbstractCategoryStorageTransfers
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return void
     */
    protected function removeLocalizedProductAbstractCategoryStorages(
        array $relatedToStoreProductAbstractCategoryStorageTransfers,
        int $idProductAbstract,
        string $storeName
    ): void {
        foreach ($relatedToStoreProductAbstractCategoryStorageTransfers as $localeName => $productAbstractCategoryStorageTransfer) {
            if (!$productAbstractCategoryStorageTransfer) {
                continue;
            }

            $this->productCategoryStorageEntityManager->deleteProductAbstractCategoryStorage(
                $idProductAbstract,
                $storeName,
                $localeName
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer[] $productCategoryTransfers
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    protected function getRelatedProductCategoriesFromCategoryTree(
        array $productCategoryTransfers,
        string $storeName,
        string $localeName
    ): array {
        $relatedProductCategoryTransfers = [];

        foreach ($productCategoryTransfers as $productCategoryTransfer) {
            $relatedProductCategoryTransfers[] = $this->generateProductCategoryStorageTransfers($productCategoryTransfer, $storeName, $localeName);
        }

        return array_merge(...$relatedProductCategoryTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer $productCategoryTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    protected function generateProductCategoryStorageTransfers(
        ProductCategoryTransfer $productCategoryTransfer,
        string $storeName,
        string $localeName
    ): array {
        $productCategoryTransfers = [];

        foreach ($productCategoryTransfer->getCategory()->getNodeCollection()->getNodes() as $nodeTransfer) {
            $productCategories = $this->extractProductCategoriesFromCategoryTree($nodeTransfer, $storeName, $localeName);

            $productCategoryTransfers[] = $this->mapProductCategoryStorageTransfers($productCategories);
        }

        return array_merge(...$productCategoryTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return array
     */
    protected function extractProductCategoriesFromCategoryTree(
        NodeTransfer $nodeTransfer,
        string $storeName,
        string $localeName
    ): array {
        $productCategories = [];
        $categoryPaths = $this->getCategoriesFromCategoryTree($nodeTransfer->getIdCategoryNode());

        foreach ($categoryPaths as $idCategoryNode => $categoryPath) {
            if ($categoryPath[static::COL_STORE] === $storeName && $categoryPath[static::COL_LOCALE] === $localeName) {
                $productCategories[] = $categoryPath;
            }
        }

        return $productCategories;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer[][]
     */
    protected function findProductCategories(array $productAbstractIds): array
    {
        $mappedProductCategoryTransfers = [];
        $productCategoryTransfers = $this->productCategoryStorageRepository
            ->getProductCategoryWithCategoryNodes($productAbstractIds);

        foreach ($productCategoryTransfers as $productCategoryTransfer) {
            $mappedProductCategoryTransfers[$productCategoryTransfer->getFkProductAbstract()][] = $productCategoryTransfer;
        }

        return $mappedProductCategoryTransfers;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return array
     */
    protected function getCategoriesFromCategoryTree(int $idCategoryNode): array
    {
        if (static::$categoryTree === null) {
            static::$categoryTree = $this->categoryTreeLoader->loadCategoryTree();
        }

        return static::$categoryTree[$idCategoryNode] ?? [];
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

    /**
     * @param array $productCategories
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    protected function mapProductCategoryStorageTransfers(array $productCategories): array
    {
        $productCategoryTransfers = [];

        foreach ($productCategories as $productCategory) {
            $productCategoryTransfers[] = (new ProductCategoryStorageTransfer())
                ->setCategoryNodeId((int)$productCategory[static::COL_ID_CATEGORY_NODE])
                ->setCategoryId((int)$productCategory[static::COL_FK_CATEGORY])
                ->setUrl($productCategory[static::COL_URL])
                ->setName($productCategory[static::COL_NAME]);
        }

        return $productCategoryTransfers;
    }
}
