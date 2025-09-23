<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 */
class ProductPageSearchFacade extends AbstractFacade implements ProductPageSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->getFactory()
            ->createProductAbstractPagePublisher()
            ->publish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    public function publishWithTimestamp(array $productAbstractIdTimestampMap): void
    {
        $this->getFactory()
            ->createProductAbstractPagePublisher()
            ->publishWithTimestamps($productAbstractIdTimestampMap);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     * @param array<string> $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, $pageDataExpanderPluginNames = [])
    {
        $this->getFactory()
            ->createProductAbstractPagePublisher()
            ->refresh($productAbstractIds, $pageDataExpanderPluginNames);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $this->getFactory()
            ->createProductAbstractPagePublisher()
            ->unpublish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishProductConcretes(array $productIds): void
    {
        $this->getFactory()
            ->createProductConcretePageSearchPublisher()
            ->publish($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function unpublishProductConcretes(array $productIds): void
    {
        $this->getFactory()
            ->createProductConcretePageSearchPublisher()
            ->unpublish($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return void
     */
    public function unpublishProductConcretePageSearches(array $productAbstractStoreMap): void
    {
        $this->getFactory()
            ->createProductConcretePageSearchUnpublisher()
            ->unpublishByAbstractProductsAndStores($productAbstractStoreMap);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function getProductConcretePageSearchTransfersByProductIds(array $productIds): array
    {
        return $this->getFactory()
            ->createProductConcretePageSearchReader()
            ->getProductConcretePageSearchTransfersByProductIds($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publishProductConcretePageSearchesByProductAbstractIds(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createProductConcretePageSearchPublisher()
            ->publishProductConcretePageSearchesByProductAbstractIds($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function expandProductConcretePageSearchTransferWithProductImages(
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer {
        return $this->getFactory()
            ->createProductConcretePageSearchExpander()
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array<string, mixed> $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductPageMapWithCategoryData(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        return $this->getFactory()
            ->createProductPageMapCategoryExpander()
            ->expandProductPageMapWithCategoryData(
                $pageMapTransfer,
                $pageMapBuilder,
                $productData,
                $localeTransfer,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $priceProductStoreIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByPriceProductStoreIds(array $priceProductStoreIds): array
    {
        return $this->getRepository()->getProductAbstractIdsByPriceProductStoreIds($priceProductStoreIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageLoadTransferWithPriceData(ProductPageLoadTransfer $productPageLoadTransfer): ProductPageLoadTransfer
    {
        return $this->getFactory()->createPriceProductPageExpander()->expandProductPageLoadTransferWithPricesData($productPageLoadTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndProductIds(FilterTransfer $filterTransfer, array $productIds = []): array
    {
        return $this->getRepository()
            ->getSynchronizationDataTransfersByFilterAndProductIds($filterTransfer, $productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function refreshProductAbstractPage(): void
    {
        $this->getFactory()
            ->createProductAbstractPageRefresher()
            ->refresh();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductConcretePageSearchCollectionByProductEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductConcretePageSearchByProductEventsWriter()
            ->writeProductConcretePageSearchCollectionByProductEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductAbstractPageSearchCollectionByProductImageSetToProductImageEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductAbstractPageSearchWriter()
            ->writeProductAbstractPageSearchCollectionByProductImageSetToProductImageEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductAbstractPageSearchCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductAbstractPageSearchWriter()
            ->writeProductAbstractPageSearchCollectionByCategoryStoreEvents($eventEntityTransfers);
    }
}
