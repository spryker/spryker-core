<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MerchantProductOptionFacadeInterface
{
    /**
     * Specification:
     * - Gets merchant product option group collection by provided criteria.
     * - Returns `MerchantProductOptionGroupCollection` transfer object.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MerchantProductOption\Business\MerchantProductOptionFacadeInterface::getMerchantProductOptionGroupCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer
     */
    public function getGroups(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): MerchantProductOptionGroupCollectionTransfer;

    /**
     * Specification:
     * - Fetches a collection of product option groups from the Persistence.
     * - Uses `MerchantProductOptionGroupCriteriaTransfer.MerchantProductOptionGroupConditions.productOptionGroupIds` to filter product option groups by productOptionGroupIds.
     * - Uses `MerchantProductOptionGroupCriteriaTransfer.pagination.limit` and `MerchantProductOptionGroupCriteriaTransfer.pagination.offset` to paginate results with limit and offset.
     * - Returns `MerchantProductOptionGroupCollectionTransfer` filled with found product option groups.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer
     */
    public function getMerchantProductOptionGroupCollection(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): MerchantProductOptionGroupCollectionTransfer;

    /**
     * Specification:
     *  - Validates that all merchant product option groups in the cart have `approved` status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateMerchantProductOptionsInCart(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     *  - Checks that all merchant product option groups in the quote have `approved` status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateMerchantProductOptionsOnCheckout(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): CheckoutResponseTransfer;

    /**
     * Specification:
     * - Expands `ProductOptionGroup` transfer object with merchant data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function expandProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer): ProductOptionGroupTransfer;
}
