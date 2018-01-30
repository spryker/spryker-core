<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductCustomerPermissionFacadeInterface
{
    /**
     * Specification:
     *  - Add one product to the customer permission list
     *
     * @api
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function saveCustomerProductPermission(int $customerId, int $productId);

    /**
     * Specification:
     *  - Add new products to customer, if they are no assigned yet
     *
     * @api
     *
     * @param int $customerId
     * @param array $productIds
     *
     * @return void
     */
    public function saveCustomerProductPermissions(int $customerId, array $productIds);

    /**
     * Specification:
     *  - Delete one product from the customer permission list
     *
     * @api
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function deleteCustomerProductPermission(int $customerId, int $productId);

    /**
     * Specification:
     *  - Delete all products from the customer permission list
     *
     * @api
     *
     * @param int $customerId
     *
     * @return void
     */
    public function deleteCustomerProductPermissions(int $customerId);

    /**
     * Specification:
     * - Checks added to cart products for current customer permissions
     * - Returns pre-check transfer with error messages (in negative case)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     **/
    public function checkPermissions(CartChangeTransfer $cartChangeTransfer);

    /**
     * Specification:
     * - Checks if customer has permission to buy all products in cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkoutPreCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);
}
