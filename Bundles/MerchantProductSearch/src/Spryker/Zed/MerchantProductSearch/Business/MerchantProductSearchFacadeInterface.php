<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

interface MerchantProductSearchFacadeInterface
{
    /**
     * Specification:
     *  - Gets merchant ids from eventTransfers.
     *  - Retrieves a list of abstract product ids by merchant ids.
     *  - Queries all product abstract with the given abstract product ids.
     *  - Calls product page search facade to refresh stored data.
     *
     * @api
     *
     * @deprecated Will be removed in the next major without replacement due to performance reasons and delegation of product rebuilding through product events.
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     *  - Gets abstract product merchant ids from eventTransfers.
     *  - Queries all product abstract with the given abstract product merchant ids.
     *  - Calls product page search facade to refresh stored data.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantProductAbstractEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Returns a list of ProductAbstractMerchantTransfers with data about merchant by product abstract ids.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractMerchantTransfer>
     */
    public function getMerchantDataByProductAbstractIds(array $productAbstractIds): array;

    /**
     * Specification:
     * - Expands the provided PageMap transfer with merchant product data.
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
    ): PageMapTransfer;
}
