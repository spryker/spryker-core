<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface getRepository()
 */
class MerchantProductOfferSearchFacade extends AbstractFacade implements MerchantProductOfferSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major without replacement due to performance reasons and delegation of product rebuilding through product events.
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createMerchantProductOfferSearchWriter()
            ->writeCollectionByIdMerchantEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdProductOfferEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createMerchantProductOfferSearchWriter()
            ->writeCollectionByIdProductOfferEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteCollectionByProductOfferEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createMerchantProductOfferSearchWriter()
            ->writeProductConcreteCollectionByIdProductOfferEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteCollectionByProductOfferStoreEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createMerchantProductOfferSearchWriter()
            ->writeProductConcreteCollectionByIdProductOfferStoreEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacade::getProductAbstractMerchantCollection()} instead.
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractMerchantTransfer>
     */
    public function getProductAbstractMerchantDataByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createMerchantProductOfferSearchReader()
            ->getProductAbstractMerchantDataByProductAbstractIds($productAbstractIds);
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
    public function expandProductConcretePageMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        return $this->getFactory()
            ->createMerchantProductOfferSearchExpander()
            ->expandProductConcretePageMap(
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
     * @param \Generated\Shared\Transfer\ProductAbstractMerchantCriteriaTransfer $productAbstractMerchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantCollectionTransfer
     */
    public function getProductAbstractMerchantCollection(
        ProductAbstractMerchantCriteriaTransfer $productAbstractMerchantCriteriaTransfer
    ): ProductAbstractMerchantCollectionTransfer {
        return $this->getRepository()->getProductAbstractMerchantCollection($productAbstractMerchantCriteriaTransfer);
    }
}
