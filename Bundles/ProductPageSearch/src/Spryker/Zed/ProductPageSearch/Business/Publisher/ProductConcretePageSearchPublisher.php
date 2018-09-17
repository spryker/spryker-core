<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductPageSearch\Business\Exception\ProductConcretePageSearchNotFoundException;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface;
use Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapper;
use Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapperInterface;

class ProductConcretePageSearchPublisher implements ProductConcretePageSearchPublisherInterface
{
    use TransactionTrait;

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
     * @var \Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapperInterface
     */
    protected $productPageSearchMapper;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @var \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[]
     */
    protected $pageDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface $productConcretePageSearchReader
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface $productFacade
     * @param \Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapperInterface $productPageSearchMapper
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface $utilEncoding
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[] $pageDataExpanderPlugins
     */
    public function __construct(
        ProductConcretePageSearchReaderInterface $productConcretePageSearchReader,
        ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter,
        ProductPageSearchToProductInterface $productFacade,
        ProductPageSearchMapperInterface $productPageSearchMapper,
        ProductPageSearchToUtilEncodingInterface $utilEncoding,
        array $pageDataExpanderPlugins
    ) {
        $this->productConcretePageSearchReader = $productConcretePageSearchReader;
        $this->productConcretePageSearchWriter = $productConcretePageSearchWriter;
        $this->productFacade = $productFacade;
        $this->productPageSearchMapper = $productPageSearchMapper;
        $this->pageDataExpanderPlugins = $pageDataExpanderPlugins;
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function publish(array $productConcreteIds): void
    {
        $productConcreteTransfers = $this->productFacade->findProductConcretesByProductConcreteIds($productConcreteIds);
        $productConcretePageSearchTransfers = $this->productConcretePageSearchReader->findProductConcretePageSearchTransfersByProductConcreteIdsGrouppedByStoreAndLocale($productConcreteIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteTransfers, $productConcretePageSearchTransfers) {
            $this->executePublishTransaction($productConcreteTransfers, $productConcretePageSearchTransfers);
        });
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function unpublish(array $productConcreteIds): void
    {
        $productConcretePageSearchTransfers = $this->productConcretePageSearchReader->findProductConcretePageSearchTransfersByProductConcreteIdsGrouppedByStoreAndLocale($productConcreteIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcretePageSearchTransfers) {
            $this->executeUnpublishTransaction($productConcretePageSearchTransfers);
        });
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
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
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
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $productConcretePageSearchTransfer = $localizedProductConcretePageSearchTransfers[$localizedAttribute->getLocale()->getLocaleName()] ?? new ProductConcretePageSearchTransfer();

            if (!$productConcreteTransfer->getIsActive() && $productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
                $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
            }

            $productConcretePageSearchTransfer = $this->productPageSearchMapper->mapProductConcreteTransferToProductConcretePageSearchTransfer(
                $productConcreteTransfer,
                $productConcretePageSearchTransfer,
                $storeTransfer,
                $localizedAttribute
            );

            $productConcretePageSearchTransfer = $this->expandProductConcretePageSearchTransferWithPlugins($productConcreteTransfer, $productConcretePageSearchTransfer);

            $productConcretePageSearchTransfer->setData(
                $this->productPageSearchMapper->mapToSearchData($productConcretePageSearchTransfer)
            );

            $productConcretePageSearchTransfer->setStructuredData(
                $this->getStructuredDataFromProductConcretePageSearchTransfer($productConcretePageSearchTransfer)
            );

            $this->productConcretePageSearchWriter->saveProductConcretePageSearch($productConcretePageSearchTransfer);
        }
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
        unset($data[ProductPageSearchMapper::IDENTIFIER_PRODUCT_CONCRETE_PAGE_SEARCH]);

        return $this->utilEncoding->encodeJson($data);
    }
}
