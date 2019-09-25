<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business;

use Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;

interface CartsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Finds customer quote by uuid.
     * - uuid and customerReference must be set in the QuoteTransfer taken as parameter.
     * - Checks user permission to read shared cart if QuoteTransfer.Customer.CompanyUserTransfer.idCompanyUser is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Retrieves customer quote collection filtered by criteria.
     * - Filters by customer reference when provided.
     * - Filters by current store ID.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer;

    /**
     * Specification:
     * - Updates existing quote entity from QuoteTransfer.
     * - uuid and CustomerTransfer must be set in the QuoteTransfer.
     * - Reloads all items in cart.
     * - Checks user permission to update shared cart if QuoteTransfer.Customer.CompanyUserTransfer.idCompanyUser is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Creates new quote entity.
     * - CustomerTransfer must be set in the QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Creates a single quote for customer.
     * - Creating of more than one quote is not allowed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createSingleQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Removes quote from DB.
     * - uuid and CustomerTransfer must be set in the QuoteTransfer.
     * - Checks user permission to delete shared cart if QuoteTransfer.Customer.CompanyUserTransfer.idCompanyUser is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Updates cart item quantity.
     * - quoteUuid, customerReference, sku and quantity must be set in the RestCartItemsAttributesTransfer.
     * - Checks user permission to update an item of shared cart if RestCartItemsAttributesTransfer.Customer.CompanyUserTransfer.idCompanyUser is set.
     *
     * @api
     *
     * @deprecated use changeItemQuantity() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateItem(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Updates cart item quantity.
     * - quoteUuid, CustomerTransfer.customerReference, sku and quantity must be set in the CartItemRequestTransfer.
     * - Checks user permission to update an item of shared cart if CartItemRequestTransfer.Customer.CompanyUserTransfer.idCompanyUser is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateItemQuantity(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Adds an item to the cart.
     * - quoteUuid, customerReference, and sku must be set in the RestCartItemsAttributesTransfer.
     * - Checks user permission to add an item to shared cart if RestCartItemsAttributesTransfer.Customer.CompanyUserTransfer.idCompanyUser is set.
     *
     * @api
     *
     * @deprecated Use addToCart() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItem(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Adds an item to the cart.
     * - quoteUuid, CustomerTransfer.customerReference and sku must be set in the CartItemRequestTransfer.
     * - Checks user permission to add an item to shared cart if RestCartItemsAttributesTransfer.Customer.CompanyUserTransfer.idCompanyUser is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addToCart(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Removes item from cart.
     * - quoteUuid, customerReference, sku must be set in the RestCartItemsAttributesTransfer.
     * - Checks user permission to delete an item from shared cart if RestCartItemsAttributesTransfer.Customer.CompanyUserTransfer.idCompanyUser is set.
     *
     * @api
     *
     * @deprecated Use removeItem() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteItem(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Removes item from cart.
     * - quoteUuid, CustomerTransfer.customerReference, sku must be set in the CartItemRequestTransfer.
     * - Checks user permission to delete an item from shared cart if RestCartItemsAttributesTransfer.Customer.CompanyUserTransfer.idCompanyUser is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeItem(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Adds an item to the guest cart.
     * - sku, quantity and customerReference must be set in the RestCartItemsAttributesTransfer.
     *
     * @api
     *
     * @deprecated Use addToGuestCart() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItemToGuestCart(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer;

 /**
  * Specification:
  * - Adds an item to the guest cart.
  * - sku, quantity and CustomerTransfer.customerReference must be set in the CartItemRequestTransfer.
  *
  * @api
  *
  * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
  *
  * @return \Generated\Shared\Transfer\QuoteResponseTransfer
  */
    public function addToGuestCart(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Transforms a guest cart to the regular cart.
     * - anonymousCustomerReference and customerReference must be set in the AssignGuestQuoteRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function assignGuestCartToRegisteredCustomer(AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer): QuoteResponseTransfer;
}
