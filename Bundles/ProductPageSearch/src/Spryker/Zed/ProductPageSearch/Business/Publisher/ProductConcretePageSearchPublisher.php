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
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductPageSearch\Business\Exception\ProductConcretePageSearchNotFoundException;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface;

class ProductConcretePageSearchPublisher implements ProductConcretePageSearchPublisherInterface
{
    use TransactionTrait;

    protected const IDENTIFIER_PRODUCT_CONCRETE_PAGE_SEARCH = 'id_product_concrete_page_search';
    protected const IDENTIFIER_STRUCTURED_DATA = 'structured_data';

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface
     */
    protected $productConcretePageSearchReader;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface
     */
    protected $productConcretePageSearchWriter;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface
     */
    protected $searchFacade;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[]
     */
    protected $pageDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface $productConcretePageSearchReader
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface $productFacade
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface $utilEncoding
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface $searchFacade
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[] $pageDataExpanderPlugins
     */
    public function __construct(
        ProductConcretePageSearchReaderInterface $productConcretePageSearchReader,
        ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter,
        ProductPageSearchToProductInterface $productFacade,
        ProductPageSearchToUtilEncodingInterface $utilEncoding,
        ProductPageSearchToSearchInterface $searchFacade,
        ProductPageSearchToStoreFacadeInterface $storeFacade,
        array $pageDataExpanderPlugins
    ) {
        $this->productConcretePageSearchReader = $productConcretePageSearchReader;
        $this->productConcretePageSearchWriter = $productConcretePageSearchWriter;
        $this->productFacade = $productFacade;
        $this->pageDataExpanderPlugins = $pageDataExpanderPlugins;
        $this->searchFacade = $searchFacade;
        $this->utilEncoding = $utilEncoding;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void
    {
        $productConcreteTransfers = $this->productFacade->getProductConcreteTransfersByProductIds($productIds);
        $productConcretePageSearchTransfers = $this->productConcretePageSearchReader->getProductConcretePageSearchTransfersByProductIdsGrouppedByStoreAndLocale($productIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteTransfers, $productConcretePageSearchTransfers) {
            $this->executePublishTransaction($productConcreteTransfers, $productConcretePageSearchTransfers);
        });
    }

    /**
     * @param int[] $productAbstractIds
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
     * @param int[] $productIds
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
     * @param array $productConcreteTransfers
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
     *
     * @return void
     */
    protected function executePublishTransaction(array $productConcreteTransfers, array $productConcretePageSearchTransfers): void
    {
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productConcreteTransfer->getStores() as $storeTransfer) {
                $this->syncProductConcretePageSearchPerStore(
                    $productConcreteTransfer,
                    $storeTransfer,
                    $productConcretePageSearchTransfers[$productConcreteTransfer->getIdProductConcrete()][$storeTransfer->getName()] ?? []
                );
            }
        }
    }

    /**
     * @param array $productConcretePageSearchTransfers
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
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $localizedProductConcretePageSearchTransfers
     *
     * @return void
     */
    protected function syncProductConcretePageSearchPerStore(
        ProductConcreteTransfer $productConcreteTransfer,
        StoreTransfer $storeTransfer,
        array $localizedProductConcretePageSearchTransfers
    ): void {
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $this->syncProductConcretePageSearchPerLocale(
                $productConcreteTransfer,
                $storeTransfer,
                $localizedProductConcretePageSearchTransfers[$localizedAttributesTransfer->getLocale()->getLocaleName()] ?? new ProductConcretePageSearchTransfer(),
                $localizedAttributesTransfer
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return void
     */
    protected function syncProductConcretePageSearchPerLocale(
        ProductConcreteTransfer $productConcreteTransfer,
        StoreTransfer $storeTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): void {
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

        $this->mapProductConcretePageSearchTransfer(
            $productConcreteTransfer,
            $storeTransfer,
            $productConcretePageSearchTransfer,
            $localizedAttributesTransfer
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
            $localizedAttributesTransfer
        );

        $productConcretePageSearchTransfer = $this->expandProductConcretePageSearchTransferWithPlugins($productConcreteTransfer, $productConcretePageSearchTransfer);

        $productConcretePageSearchTransfer->setData(
            $this->mapTransferToProductConcretePageSearchDocument($productConcretePageSearchTransfer)
        );

        $productConcretePageSearchTransfer->setStructuredData(
            $this->getStructuredDataFromProductConcretePageSearchTransfer($productConcretePageSearchTransfer)
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
            true
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

        return $this->searchFacade->transformPageMapToDocumentByMapperName(
            $productConcretePageSearchData,
            $localeTransfer,
            ProductPageSearchConstants::PRODUCT_CONCRETE_RESOURCE_NAME
        );
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
    protected function expandProductConcretePageSearchTransferWithPlugins(ProductConcreteTransfer $productConcreteTransfer, ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): ProductConcretePageSearchTransfer
    {
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
}
