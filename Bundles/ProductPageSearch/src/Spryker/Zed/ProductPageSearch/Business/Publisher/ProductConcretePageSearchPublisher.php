<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper;
use Spryker\Zed\ProductPageSearch\Business\Exception\ProductConcretePageSearchNotFoundException;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchConfig;

class ProductConcretePageSearchPublisher implements ProductConcretePageSearchPublisherInterface
{
    use TransactionTrait;
    use InstancePoolingTrait;

    /**
     * @var array<int>
     */
    protected static array $publishedProductConcreteIds = [];

    /**
     * @var array<int>
     */
    protected static array $unpublishedProductConcreteIds = [];

    /**
     * @var string
     */
    protected const IDENTIFIER_PRODUCT_CONCRETE_PAGE_SEARCH = 'id_product_concrete_page_search';

    /**
     * @var string
     */
    protected const IDENTIFIER_STRUCTURED_DATA = 'structured_data';

    /**
     * @var array<int, int>
     */
    protected array $productIdTimestampMapToSave = [];

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface $repository
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface $productConcretePageSearchReader
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface $productFacade
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface $utilEncoding
     * @param \Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper $productConcreteSearchDataMapper
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchInterface $productPageSearchFacade
     * @param \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig $productPageSearchConfig
     * @param array<\Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface> $pageDataExpanderPlugins
     * @param array<\Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcreteCollectionFilterPluginInterface> $productConcreteCollectionFilterPlugins
     */
    public function __construct(
        protected ProductPageSearchRepositoryInterface $repository,
        protected ProductConcretePageSearchReaderInterface $productConcretePageSearchReader,
        protected ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter,
        protected ProductPageSearchToProductInterface $productFacade,
        protected ProductPageSearchToUtilEncodingInterface $utilEncoding,
        protected AbstractProductSearchDataMapper $productConcreteSearchDataMapper,
        protected ProductPageSearchToStoreFacadeInterface $storeFacade,
        protected ProductPageSearchToProductSearchInterface $productPageSearchFacade,
        protected ProductPageSearchConfig $productPageSearchConfig,
        protected array $pageDataExpanderPlugins,
        protected array $productConcreteCollectionFilterPlugins
    ) {
    }

    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return void
     */
    public function publishWithTimestamp(array $productIdTimestampMap): void
    {
        // Filters IDs if it had been processed in the current process
        $productIds = array_values(array_unique(array_diff(array_keys($productIdTimestampMap), static::$publishedProductConcreteIds)));
        // Exclude IDs if they were processed in current process
        $productIdTimestampMap = array_intersect_key($productIdTimestampMap, array_flip($productIds));
        // Filters IDs if it had been processed in parallel processes
        $this->productIdTimestampMapToSave = $this->repository->getRelevantProductConcreteIdsToUpdate($productIdTimestampMap);

        if ($this->productIdTimestampMapToSave) {
            $this->publish(array_keys($this->productIdTimestampMapToSave));
        }
        static::$publishedProductConcreteIds = array_merge(static::$publishedProductConcreteIds, $productIds);
    }

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void
    {
        $isPoolingStateChanged = $this->disableInstancePooling();

        $productIds = array_unique(array_filter($productIds));

        if (!$productIds) {
            return;
        }

        $productConcreteIdsChunks = array_chunk(
            $productIds,
            $this->productPageSearchConfig->getProductConcretePagePublishChunkSize(),
        );

        foreach ($productConcreteIdsChunks as $productConcreteIdsChunk) {
            $productConcreteTransfers = $this->productFacade->getProductConcreteTransfersByProductIds($productConcreteIdsChunk);
            $productConcretePageSearchTransfers = $this->productConcretePageSearchReader
                ->getProductConcretePageSearchTransfersByProductIdsGrouppedByStoreAndLocale($productConcreteIdsChunk);

            $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteTransfers, $productConcretePageSearchTransfers) {
                $this->executePublishTransaction($productConcreteTransfers, $productConcretePageSearchTransfers);
            });
        }

        if ($isPoolingStateChanged) {
            $this->enableInstancePooling();
        }
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publishProductConcretePageSearchesByProductAbstractIds(array $productAbstractIds): void
    {
        $productConcreteTransfers = $this->productFacade->getProductConcreteTransfersByProductAbstractIds($productAbstractIds);
        $productIds = $this->getProductIdsListFromProductConcreteTransfers($productConcreteTransfers);
        $productConcretePageSearchTransfers = $this->productConcretePageSearchReader->getProductConcretePageSearchTransfersByProductIdsGrouppedByStoreAndLocale($productIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteTransfers, $productConcretePageSearchTransfers) {
            $this->executePublishTransaction($productConcreteTransfers, $productConcretePageSearchTransfers);
        });
    }

    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return void
     */
    public function unpublishWithTimestamp(array $productIdTimestampMap): void
    {
        $productIds = array_values(array_unique(array_diff(array_keys($productIdTimestampMap), static::$unpublishedProductConcreteIds)));
        if ($productIds) {
            $this->unpublish($productIds);
        }

        static::$unpublishedProductConcreteIds = array_merge(static::$unpublishedProductConcreteIds, $productIds);
    }

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function unpublish(array $productIds): void
    {
        $productConcretePageSearchTransfers = $this->productConcretePageSearchReader->getProductConcretePageSearchTransfersByProductIds($productIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcretePageSearchTransfers) {
            $this->executeUnpublishTransaction($productConcretePageSearchTransfers);
        });
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array
     */
    protected function getProductIdsListFromProductConcreteTransfers(array $productConcreteTransfers): array
    {
        $productIds = [];
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productIds[] = $productConcreteTransfer->getIdProductConcrete();
        }

        return $productIds;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     * @param array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer> $productConcretePageSearchTransfers
     *
     * @return void
     */
    protected function executePublishTransaction(array $productConcreteTransfers, array $productConcretePageSearchTransfers): void
    {
        $filteredProductConcreteTransfers = $this->executeProductConcreteCollectionFilterPlugins($productConcreteTransfers);
        $filteredProductIds = $this->getProductIdsListFromProductConcreteTransfers($filteredProductConcreteTransfers);
        $productConcreteTransfers = $this->productPageSearchFacade->expandProductConcreteTransfersWithIsSearchable(
            $productConcreteTransfers,
        );

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productConcreteTransfer->getStores() as $storeTransfer) {
                $this->syncProductConcretePageSearchPerStore(
                    $productConcreteTransfer,
                    $storeTransfer,
                    $productConcretePageSearchTransfers[$productConcreteTransfer->getIdProductConcrete()][$storeTransfer->getName()] ?? [],
                    $filteredProductIds,
                );
            }
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer> $productConcretePageSearchTransfers
     *
     * @return void
     */
    protected function executeUnpublishTransaction(array $productConcretePageSearchTransfers): void
    {
        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer> $localizedProductConcretePageSearchTransfers
     * @param array<int> $filteredProductIds
     *
     * @return void
     */
    protected function syncProductConcretePageSearchPerStore(
        ProductConcreteTransfer $productConcreteTransfer,
        StoreTransfer $storeTransfer,
        array $localizedProductConcretePageSearchTransfers,
        array $filteredProductIds
    ): void {
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $this->syncProductConcretePageSearchPerLocale(
                $productConcreteTransfer,
                $storeTransfer,
                $localizedProductConcretePageSearchTransfers[$localizedAttributesTransfer->getLocale()->getLocaleName()] ?? new ProductConcretePageSearchTransfer(),
                $localizedAttributesTransfer,
                $filteredProductIds,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     * @param array<int> $filteredProductIds
     *
     * @return void
     */
    protected function syncProductConcretePageSearchPerLocale(
        ProductConcreteTransfer $productConcreteTransfer,
        StoreTransfer $storeTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        LocalizedAttributesTransfer $localizedAttributesTransfer,
        array $filteredProductIds
    ): void {
        if (!in_array($productConcreteTransfer->getIdProductConcrete(), $filteredProductIds)) {
            if ($productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
                $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
            }

            return;
        }

        if (!$productConcreteTransfer->getIsActive() && $productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
            $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);

            return;
        }

        if (!$this->isValidStoreLocale($storeTransfer->getName(), $localizedAttributesTransfer->getLocale()->getLocaleName())) {
            if ($productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
                $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
            }

            return;
        }

        if ($localizedAttributesTransfer->getIsSearchable() === false) {
            if ($productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
                $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
            }

            return;
        }

        $this->mapProductConcretePageSearchTransfer(
            $productConcreteTransfer,
            $storeTransfer,
            $productConcretePageSearchTransfer,
            $localizedAttributesTransfer,
        );

        $this->productConcretePageSearchWriter->saveProductConcretePageSearch($productConcretePageSearchTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    protected function mapProductConcretePageSearchTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        StoreTransfer $storeTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): ProductConcretePageSearchTransfer {
        $productConcretePageSearchTransfer = $this->mapProductConcreteTransferToProductConcretePageSearchTransfer(
            $productConcreteTransfer,
            $productConcretePageSearchTransfer,
            $storeTransfer,
            $localizedAttributesTransfer,
        );

        if (!empty($this->productIdTimestampMapToSave[$productConcreteTransfer->getIdProductConcrete()])) {
            $productConcretePageSearchTransfer->setTimestamp($this->productIdTimestampMapToSave[$productConcreteTransfer->getIdProductConcrete()]);
        }

        $productConcretePageSearchTransfer = $this->expandProductConcretePageSearchTransferWithPlugins($productConcreteTransfer, $productConcretePageSearchTransfer);

        $productConcretePageSearchTransfer->setData(
            $this->mapTransferToProductConcretePageSearchDocument($productConcretePageSearchTransfer),
        );

        $productConcretePageSearchTransfer->setStructuredData(
            $this->getStructuredDataFromProductConcretePageSearchTransfer($productConcretePageSearchTransfer),
        );

        return $productConcretePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    protected function mapProductConcreteTransferToProductConcretePageSearchTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        StoreTransfer $storeTransfer,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): ProductConcretePageSearchTransfer {
        $productConcretePageSearchTransfer->fromArray(
            $productConcreteTransfer->toArray(),
            true,
        );

        $productConcretePageSearchTransfer->setFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->setFkProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setType(ProductPageSearchConstants::PRODUCT_CONCRETE_RESOURCE_NAME)
            ->setStore($storeTransfer->getName())
            ->setLocale($localizedAttributesTransfer->getLocale()->getLocaleName())
            ->setName($localizedAttributesTransfer->getName());

        return $productConcretePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return array
     */
    protected function mapTransferToProductConcretePageSearchDocument(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): array
    {
        $productConcretePageSearchData = $productConcretePageSearchTransfer->toArray(true, true);
        $localeTransfer = (new LocaleTransfer())
            ->setLocaleName($productConcretePageSearchTransfer->getLocale());

        return $this->productConcreteSearchDataMapper->mapProductDataToSearchData($productConcretePageSearchData, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @throws \Spryker\Zed\ProductPageSearch\Business\Exception\ProductConcretePageSearchNotFoundException
     *
     * @return void
     */
    protected function deleteProductConcretePageSearch(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): void
    {
        if (!$this->productConcretePageSearchWriter->deleteProductConcretePageSearch($productConcretePageSearchTransfer)) {
            throw new ProductConcretePageSearchNotFoundException(sprintf('Target storage entry for product with id %s not found', $productConcretePageSearchTransfer->getFkProduct()));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    protected function expandProductConcretePageSearchTransferWithPlugins(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer {
        foreach ($this->pageDataExpanderPlugins as $pageDataExpanderPlugin) {
            $productConcretePageSearchTransfer = $pageDataExpanderPlugin->expand($productConcreteTransfer, $productConcretePageSearchTransfer);
        }

        return $productConcretePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return string
     */
    protected function getStructuredDataFromProductConcretePageSearchTransfer(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): string
    {
        $data = $productConcretePageSearchTransfer->toArray();
        unset($data[static::IDENTIFIER_PRODUCT_CONCRETE_PAGE_SEARCH]);
        // Avoiding data recursion when transfer was populated from DB already
        unset($data[static::IDENTIFIER_STRUCTURED_DATA]);

        return $this->utilEncoding->encodeJson($data);
    }

    /**
     * @param string $storeName
     * @param string $localeName
     *
     * @return bool
     */
    protected function isValidStoreLocale(string $storeName, string $localeName): bool
    {
        return in_array($localeName, $this->storeFacade->getStoreByName($storeName)->getAvailableLocaleIsoCodes());
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function executeProductConcreteCollectionFilterPlugins(array $productConcreteTransfers): array
    {
        foreach ($this->productConcreteCollectionFilterPlugins as $productConcreteCollectionFilterPlugin) {
            $productConcreteTransfers = $productConcreteCollectionFilterPlugin->filter($productConcreteTransfers);
        }

        return $productConcreteTransfers;
    }
}
