<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductOfferFacadeInterface
{
    /**
     * Specification:
     * - Returns collection of product offer by provided criteria.
     * - Pagination is controlled with page, maxPerPage, nbResults, previousPage, nextPage, firstIndex, lastIndex, firstPage and lastPage values.
     * - Result might be filtered with concreteSku(s), productOfferReference(s) values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer;

    /**
     * Specification:
     * - Finds ProductOfferTransfer by provided ProductOfferCriteriaFilterTransfer.
     * - Result might be filtered with concreteSku(s), productOfferReference(s) values.
     * - Executes ProductOfferExpanderPluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ?ProductOfferTransfer;

    /**
     * Specification:
     * - Generates product offer reference and sets default approval status if they are not set.
     * - Creates a product offer.
     * - Creates relations between a product offer and stores.
     * - Executes ProductOfferPostCreatePluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function create(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Returns ProductOfferResponseTransfer.isSuccessful=false if $productOfferTransfer.idProductOffer is not given.
     * - Returns ProductOfferResponseTransfer.isSuccessful=false if no offer is found with $productOfferTransfer.idProductOffer.
     * - Persists product offer entity with modified fields from ProductOfferTransfer.
     * - Returns new product offer entity in ProductOfferResponseTransfer.productOffer and isSuccessful=true.
     * - Updates relations between a product offer and stores.
     * - Executes ProductOfferPostUpdatePluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferResponseTransfer
     */
    public function update(ProductOfferTransfer $productOfferTransfer): ProductOfferResponseTransfer;

    /**
     * Specification:
     *  - Removes inactive offer items from quote.
     *  - Adds info messages for the removed product offers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveProductOfferItems(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Check if cart items product offer belongs to product.
     * - Returns pre-check transfer with error messages in case of error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkItemProductOffer(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Gets the available product offer statuses for the current product offer status.
     * - Returns empty array if no available statuses exist.
     *
     * @api
     *
     * @param string $currentStatus
     *
     * @return string[]
     */
    public function getApplicableApprovalStatuses(string $currentStatus): array;

    /**
     * Specification:
     * - Returns `false` response if at least one quote item transfer has items with inactive or not approved ProductOffer.
     * - Sets error messages to checkout response, in case if items contain inactive or not approved ProductOffer items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateCheckoutProductOffer(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool;
}
