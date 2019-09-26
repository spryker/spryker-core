<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\ProductPageSearch\Business\Exception\PluginNotFoundException;
use Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface;
use Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface;

class ProductAbstractPagePublisher implements ProductAbstractPagePublisherInterface
{
    public const PRODUCT_ABSTRACT_LOCALIZED_ENTITY = 'PRODUCT_ABSTRACT_LOCALIZED_ENTITY';
    public const PRODUCT_ABSTRACT_PAGE_SEARCH_ENTITY = 'PRODUCT_ABSTRACT_PAGE_SEARCH_ENTITY';
    public const STORE_NAME = 'STORE_NAME';
    public const LOCALE_NAME = 'LOCALE_NAME';

    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface[]
     */
    protected $productPageDataLoaderPlugins = [];

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[]
     */
    protected $pageDataExpanderPlugins = [];

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface
     */
    protected $productPageSearchMapper;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface
     */
    protected $productPageSearchWriter;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[] $pageDataExpanderPlugins
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface[] $productPageDataLoaderPlugins
     * @param \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface $productPageSearchMapper
     * @param \Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface $productPageSearchWriter
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductPageSearchQueryContainerInterface $queryContainer,
        array $pageDataExpanderPlugins,
        array $productPageDataLoaderPlugins,
        ProductPageSearchMapperInterface $productPageSearchMapper,
        ProductPageSearchWriterInterface $productPageSearchWriter,
        ProductPageSearchToStoreFacadeInterface $storeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->pageDataExpanderPlugins = $pageDataExpanderPlugins;
        $this->productPageDataLoaderPlugins = $productPageDataLoaderPlugins;
        $this->productPageSearchMapper = $productPageSearchMapper;
        $this->productPageSearchWriter = $productPageSearchWriter;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->publishEntities($productAbstractIds, [], false);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string[] $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, array $pageDataExpanderPluginNames = [])
    {
        $this->publishEntities($productAbstractIds, $pageDataExpanderPluginNames, true);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $productAbstractPageSearchEntities = $this->findProductAbstractPageSearchEntities($productAbstractIds);

        $this->deleteProductAbstractPageSearchEntities($productAbstractPageSearchEntities);
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch[] $productAbstractPageSearchEntities
     *
     * @return void
     */
    protected function deleteProductAbstractPageSearchEntities(array $productAbstractPageSearchEntities)
    {
        foreach ($productAbstractPageSearchEntities as $productAbstractPageSearchEntity) {
            $productAbstractPageSearchEntity->delete();
        }
    }

    /**
     * @param int[] $productAbstractIds
     * @param string[] $pageDataExpanderPluginNames
     * @param bool $isRefresh
     *
     * @return void
     */
    protected function publishEntities(array $productAbstractIds, array $pageDataExpanderPluginNames, $isRefresh = false)
    {
        $pageDataExpanderPlugins = $this->getPageDataExpanderPlugins($pageDataExpanderPluginNames);

        $payloadTransfers = [];
        foreach ($productAbstractIds as $productAbstractId) {
            $payloadTransfers[$productAbstractId] = (new ProductPayloadTransfer())->setIdProductAbstract($productAbstractId);
        }

        $productPageLoadTransfer = (new ProductPageLoadTransfer())
            ->setProductAbstractIds($productAbstractIds)
            ->setPayloadTransfers($payloadTransfers);

        foreach ($this->productPageDataLoaderPlugins as $productPageDataLoaderPlugin) {
            $productPageLoadTransfer = $productPageDataLoaderPlugin->expandProductPageDataTransfer($productPageLoadTransfer);
        }

        $productAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $productCategories = $this->getProductCategoriesByProductAbstractIds($productAbstractIds);
        $productAbstractLocalizedEntities = $this->hydrateProductAbstractLocalizedEntitiesWithProductCategories($productCategories, $productAbstractLocalizedEntities);

        $productAbstractPageSearchEntities = $this->findProductAbstractPageSearchEntities($productAbstractIds);

        if (!$productAbstractLocalizedEntities) {
            $this->deleteProductAbstractPageSearchEntities($productAbstractPageSearchEntities);

            return;
        }

        $this->storeData($productAbstractLocalizedEntities, $productAbstractPageSearchEntities, $pageDataExpanderPlugins, $productPageLoadTransfer, $isRefresh);
    }

    /**
     * @param array $productAbstractLocalizedEntities
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch[] $productAbstractPageSearchEntities
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[] $pageDataExpanderPlugins
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     * @param bool $isRefresh
     *
     * @return void
     */
    protected function storeData(
        array $productAbstractLocalizedEntities,
        array $productAbstractPageSearchEntities,
        array $pageDataExpanderPlugins,
        ProductPageLoadTransfer $productPageLoadTransfer,
        $isRefresh = false
    ) {
        $pairedEntities = $this->pairProductAbstractLocalizedEntitiesWithProductAbstractPageSearchEntities(
            $productAbstractLocalizedEntities,
            $productAbstractPageSearchEntities,
            $productPageLoadTransfer
        );

        foreach ($pairedEntities as $pairedEntity) {
            $productAbstractLocalizedEntity = $pairedEntity[static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY];
            $productAbstractPageSearchEntity = $pairedEntity[static::PRODUCT_ABSTRACT_PAGE_SEARCH_ENTITY];

            if ($productAbstractLocalizedEntity === null || !$this->isActual($productAbstractLocalizedEntity)) {
                $this->deleteProductAbstractPageSearchEntity($productAbstractPageSearchEntity);

                continue;
            }

            $this->storeProductAbstractPageSearchEntity(
                $productAbstractLocalizedEntity,
                $productAbstractPageSearchEntity,
                $pairedEntity[static::STORE_NAME],
                $pairedEntity[static::LOCALE_NAME],
                $pageDataExpanderPlugins,
                $isRefresh
            );
        }
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productAbstractPageSearchEntity
     *
     * @return void
     */
    protected function deleteProductAbstractPageSearchEntity(SpyProductAbstractPageSearch $productAbstractPageSearchEntity)
    {
        if (!$productAbstractPageSearchEntity->isNew()) {
            $productAbstractPageSearchEntity->delete();
        }
    }

    /**
     * @param array $productAbstractLocalizedEntity
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productAbstractPageSearchEntity
     * @param string $storeName
     * @param string $localeName
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[] $pageDataExpanderPlugins
     * @param bool $isRefresh
     *
     * @return void
     */
    protected function storeProductAbstractPageSearchEntity(
        array $productAbstractLocalizedEntity,
        SpyProductAbstractPageSearch $productAbstractPageSearchEntity,
        $storeName,
        $localeName,
        array $pageDataExpanderPlugins,
        $isRefresh = false
    ) {
        $productPageSearchTransfer = $this->getProductPageSearchTransfer(
            $productAbstractLocalizedEntity,
            $productAbstractPageSearchEntity,
            $isRefresh
        );

        $productPageSearchTransfer->setStore($storeName);
        $productPageSearchTransfer->setLocale($localeName);

        $this->expandPageSearchTransferWithPlugins($pageDataExpanderPlugins, $productAbstractLocalizedEntity, $productPageSearchTransfer);

        $searchDocument = $this->productPageSearchMapper->mapToSearchData($productPageSearchTransfer);

        $this->productPageSearchWriter->save($productPageSearchTransfer, $searchDocument, $productAbstractPageSearchEntity);
    }

    /**
     * @param array $productAbstractLocalizedEntity
     *
     * @return bool
     */
    protected function isActual(array $productAbstractLocalizedEntity): bool
    {
        foreach ($productAbstractLocalizedEntity['SpyProductAbstract']['SpyProducts'] as $spyProduct) {
            if ($spyProduct['is_active'] && $this->isSearchable($spyProduct, $productAbstractLocalizedEntity['fk_locale'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $spyProduct
     * @param int $idLocale
     *
     * @return bool
     */
    protected function isSearchable(array $spyProduct, int $idLocale): bool
    {
        foreach ($spyProduct['SpyProductSearches'] as $spyProductSearch) {
            if ($spyProductSearch['fk_locale'] === $idLocale && $spyProductSearch['is_searchable'] === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieves the ProductPageSearchTransfer from the storage entity (if it existed already) or populates it from the localized entity.
     *
     * @param array $productAbstractLocalizedEntity
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productAbstractPageSearchEntity
     * @param bool $isRefresh
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer
     */
    protected function getProductPageSearchTransfer(
        array $productAbstractLocalizedEntity,
        SpyProductAbstractPageSearch $productAbstractPageSearchEntity,
        $isRefresh = false
    ): ProductPageSearchTransfer {
        if ($isRefresh && !$productAbstractPageSearchEntity->isNew()) {
            return $this->refreshProductPageSearchTransfer($productAbstractPageSearchEntity);
        }

        return $this->productPageSearchMapper->mapToProductPageSearchTransfer($productAbstractLocalizedEntity);
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productAbstractPageSearchEntity
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer
     */
    protected function refreshProductPageSearchTransfer(
        SpyProductAbstractPageSearch $productAbstractPageSearchEntity
    ): ProductPageSearchTransfer {
        return $this->productPageSearchMapper->mapToProductPageSearchTransferFromJson($productAbstractPageSearchEntity->getStructuredData());
    }

    /**
     * @param string[] $pageDataExpanderPluginNames
     *
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[]
     */
    protected function getPageDataExpanderPlugins(array $pageDataExpanderPluginNames)
    {
        if (!$pageDataExpanderPluginNames) {
            return $this->pageDataExpanderPlugins;
        }

        $selectedExpanderPlugins = [];
        foreach ($pageDataExpanderPluginNames as $pageDataExpanderPluginName) {
            $this->assertPageDataExpanderPluginName($pageDataExpanderPluginName);

            $selectedExpanderPlugins[] = $this->pageDataExpanderPlugins[$pageDataExpanderPluginName];
        }

        return $selectedExpanderPlugins;
    }

    /**
     * @param string $pageDataExpanderPluginName
     *
     * @throws \Spryker\Zed\ProductPageSearch\Business\Exception\PluginNotFoundException
     *
     * @return void
     */
    protected function assertPageDataExpanderPluginName($pageDataExpanderPluginName)
    {
        if (!isset($this->pageDataExpanderPlugins[$pageDataExpanderPluginName])) {
            throw new PluginNotFoundException(sprintf('The plugin with this name: %s is not found', $pageDataExpanderPluginName));
        }
    }

    /**
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[] $pageDataExpanderPlugins
     * @param array $productAbstractLocalizedEntity
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     *
     * @return void
     */
    protected function expandPageSearchTransferWithPlugins(
        array $pageDataExpanderPlugins,
        array $productAbstractLocalizedEntity,
        ProductPageSearchTransfer $productPageSearchTransfer
    ) {
        foreach ($pageDataExpanderPlugins as $pageDataExpanderPlugin) {
            $pageDataExpanderPlugin->expandProductPageData($productAbstractLocalizedEntity, $productPageSearchTransfer);
        }
    }

    /**
     * - Returns a paired array with all provided entities.
     * - ProductAbstractLocalizedEntities without ProductAbstractPageSearchEntity are paired with a newly created ProductAbstractPageSearchEntity.
     * - ProductAbstractPageSearchEntity without ProductAbstractLocalizedEntities (left outs) are paired with NULL.
     * - ProductAbstractLocalizedEntities are paired multiple times per store.
     *
     * @param array $productAbstractLocalizedEntities
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch[] $productAbstractPageSearchEntities
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return array
     */
    protected function pairProductAbstractLocalizedEntitiesWithProductAbstractPageSearchEntities(
        array $productAbstractLocalizedEntities,
        array $productAbstractPageSearchEntities,
        ProductPageLoadTransfer $productPageLoadTransfer
    ) {
        $mappedProductAbstractPageSearchEntities = $this->mapProductAbstractPageSearchEntities($productAbstractPageSearchEntities);

        $pairs = [];
        $productPayloadTransfers = $productPageLoadTransfer->getPayloadTransfers();
        foreach ($productAbstractLocalizedEntities as $productAbstractLocalizedEntity) {
            [$pairs, $mappedProductAbstractPageSearchEntities] = $this->pairProductAbstractLocalizedEntityWithProductAbstractPageSearchEntityByStoresAndLocale(
                $productAbstractLocalizedEntity['fk_product_abstract'],
                $productAbstractLocalizedEntity['Locale']['locale_name'],
                $productPayloadTransfers[$productAbstractLocalizedEntity['fk_product_abstract']],
                $productAbstractLocalizedEntity['SpyProductAbstract']['SpyProductAbstractStores'],
                $productAbstractLocalizedEntity,
                $mappedProductAbstractPageSearchEntities,
                $pairs
            );
        }

        $pairs = $this->pairRemainingProductAbstractPageSearchEntities($mappedProductAbstractPageSearchEntities, $pairs);

        return $pairs;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        $allProductAbstractLocalizedEntities = [];
        $localesByStore = [];
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $productAbstractLocalizedEntities = $this
                ->queryContainer
                ->queryProductAbstractLocalizedEntitiesByProductAbstractIdsAndStore($productAbstractIds, $storeTransfer)
                ->find()
                ->getData();

            if (!isset($localesByStore[$storeTransfer->getName()])) {
                $localesByStore[$storeTransfer->getName()] = $storeTransfer->getAvailableLocaleIsoCodes();
            }
            $productConcreteEntities = $this->getProductConcreteEntitiesWithProductSearchEntities($productAbstractIds, $localesByStore[$storeTransfer->getName()]);
            $allProductAbstractLocalizedEntities[] = $this->hydrateProductAbstractLocalizedEntitiesWithProductConcreteEntities($productConcreteEntities, $productAbstractLocalizedEntities);
        }

        return array_merge(...$allProductAbstractLocalizedEntities);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string[] $localeIsoCodes
     *
     * @return array[][]
     */
    protected function getProductConcreteEntitiesWithProductSearchEntities(array $productAbstractIds, array $localeIsoCodes): array
    {
        $productConcreteEntities = $this->getProductConcreteEntitiesByProductAbstractIdsAndLocaleIsoCodes($productAbstractIds, $localeIsoCodes);
        $productSearchEntities = $this->getProductSearchEntitiesByProductConcreteIdsAndLocaleIsoCodes(array_column($productConcreteEntities, 'id_product'), $localeIsoCodes);
        $productConcreteEntities = $this->hydrateProductConcreteEntitiesWithProductSearchEntities($productSearchEntities, $productConcreteEntities);

        return $productConcreteEntities;
    }

    /**
     * @param int[] $productAbstractIds
     * @param string[] $localeIsoCodes
     *
     * @return array
     */
    protected function getProductConcreteEntitiesByProductAbstractIdsAndLocaleIsoCodes(array $productAbstractIds, array $localeIsoCodes): array
    {
        return $this->queryContainer
            ->queryProductConcretesByAbstractProductIdsAndLocaleIsoCodes($productAbstractIds, $localeIsoCodes)
            ->find()
            ->getData();
    }

    /**
     * @return string[]
     */
    protected function getAvailableLocaleIsoCodes(): array
    {
        $localeIsoCodes = [];
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $localeIsoCodes[] = $storeTransfer->getAvailableLocaleIsoCodes();
        }

        return array_unique(array_merge(...$localeIsoCodes));
    }

    /**
     * @param int[] $productConcreteIds
     * @param string[] $localeIsoCodes
     *
     * @return array
     */
    protected function getProductSearchEntitiesByProductConcreteIdsAndLocaleIsoCodes(array $productConcreteIds, array $localeIsoCodes): array
    {
        return $this->queryContainer
            ->queryProductSearchByProductConcreteIdsAndLocaleIsoCodes($productConcreteIds, $localeIsoCodes)
            ->find()
            ->getData();
    }

    /**
     * @param array $productSearchEntities
     * @param array $productConcreteEntities
     *
     * @return array[][]
     */
    protected function hydrateProductConcreteEntitiesWithProductSearchEntities(array $productSearchEntities, array $productConcreteEntities): array
    {
        $productSearchByProductConcreteId = [];

        foreach ($productSearchEntities as $productSearch) {
            $productSearchByProductConcreteId[$productSearch['fk_product']][] = $productSearch;
        }

        foreach ($productConcreteEntities as $key => $productConcreteEntity) {
            $productConcreteId = (int)$productConcreteEntity['id_product'];
            $productConcreteEntities[$key]['SpyProductSearches'] = $productSearchByProductConcreteId[$productConcreteId];
        }

        return $productConcreteEntities;
    }

    /**
     * @param array $productCategories
     * @param array $productAbstractLocalizedEntities
     *
     * @return array[][]
     */
    protected function hydrateProductAbstractLocalizedEntitiesWithProductCategories(array $productCategories, array $productAbstractLocalizedEntities)
    {
        $productCategoriesByProductAbstractId = [];

        foreach ($productCategories as $productCategory) {
            $productCategoriesByProductAbstractId[$productCategory['fk_product_abstract']][] = $productCategory;
        }

        foreach ($productAbstractLocalizedEntities as $key => $productAbstractLocalizedEntity) {
            $productAbstractId = (int)$productAbstractLocalizedEntity['fk_product_abstract'];
            $productAbstractLocalizedEntities[$key]['SpyProductAbstract']['SpyProductCategories'] = $productCategoriesByProductAbstractId[$productAbstractId];
        }

        return $productAbstractLocalizedEntities;
    }

    /**
     * @param array $productConcreteData
     * @param array $productAbstractLocalizedEntities
     *
     * @return array[][]
     */
    protected function hydrateProductAbstractLocalizedEntitiesWithProductConcreteEntities(array $productConcreteData, array $productAbstractLocalizedEntities): array
    {
        $productConcretesByProductAbstractId = [];
        foreach ($productConcreteData as $productConcrete) {
            $productConcretesByProductAbstractId[$productConcrete['fk_product_abstract']][] = $productConcrete;
        }

        foreach ($productAbstractLocalizedEntities as $key => $productAbstractLocalizedEntity) {
            $productAbstractId = (int)$productAbstractLocalizedEntity['fk_product_abstract'];
            $productAbstractLocalizedEntities[$key]['SpyProductAbstract']['SpyProducts'] = $productConcretesByProductAbstractId[$productAbstractId];
        }

        return $productAbstractLocalizedEntities;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function getProductCategoriesByProductAbstractIds(array $productAbstractIds)
    {
        return $this->queryContainer->queryAllProductCategories($productAbstractIds)->find()->getData();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch[]
     */
    protected function findProductAbstractPageSearchEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractSearchPageByIds($productAbstractIds)->find()->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch[] $productAbstractPageSearchEntities
     *
     * @return array
     */
    protected function mapProductAbstractPageSearchEntities(array $productAbstractPageSearchEntities)
    {
        $mappedProductAbstractPageSearchEntities = [];
        foreach ($productAbstractPageSearchEntities as $entity) {
            $mappedProductAbstractPageSearchEntities[$entity->getFkProductAbstract()][$entity->getStore()][$entity->getLocale()] = $entity;
        }

        return $mappedProductAbstractPageSearchEntities;
    }

    /**
     * @param array $mappedProductAbstractPageSearchEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairRemainingProductAbstractPageSearchEntities(array $mappedProductAbstractPageSearchEntities, array $pairs)
    {
        array_walk_recursive($mappedProductAbstractPageSearchEntities, function (SpyProductAbstractPageSearch $productAbstractPageSearchEntity) use (&$pairs) {
            $pairs[] = [
                static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY => null,
                static::PRODUCT_ABSTRACT_PAGE_SEARCH_ENTITY => $productAbstractPageSearchEntity,
                static::LOCALE_NAME => $productAbstractPageSearchEntity->getLocale(),
                static::STORE_NAME => $productAbstractPageSearchEntity->getStore(),
            ];
        });

        return $pairs;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer $productPayloadTransfer
     * @param array $productAbstractStores
     * @param array $productAbstractLocalizedEntity
     * @param array $mappedProductAbstractPageSearchEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairProductAbstractLocalizedEntityWithProductAbstractPageSearchEntityByStoresAndLocale(
        $idProductAbstract,
        $localeName,
        ProductPayloadTransfer $productPayloadTransfer,
        array $productAbstractStores,
        array $productAbstractLocalizedEntity,
        array $mappedProductAbstractPageSearchEntities,
        array $pairs
    ) {
        foreach ($productAbstractStores as $productAbstractStore) {
            $storeName = $productAbstractStore['SpyStore']['name'];
            $productAbstractLocalizedEntity[ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA] = $productPayloadTransfer;

            $searchEntity = isset($mappedProductAbstractPageSearchEntities[$idProductAbstract][$storeName][$localeName]) ?
                $mappedProductAbstractPageSearchEntities[$idProductAbstract][$storeName][$localeName] :
                new SpyProductAbstractPageSearch();

            unset($mappedProductAbstractPageSearchEntities[$idProductAbstract][$storeName][$localeName]);

            $pairs[] = [
                static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY => $productAbstractLocalizedEntity,
                static::PRODUCT_ABSTRACT_PAGE_SEARCH_ENTITY => $searchEntity,
                static::LOCALE_NAME => $localeName,
                static::STORE_NAME => $storeName,
            ];
        }

        return [$pairs, $mappedProductAbstractPageSearchEntities];
    }
}
