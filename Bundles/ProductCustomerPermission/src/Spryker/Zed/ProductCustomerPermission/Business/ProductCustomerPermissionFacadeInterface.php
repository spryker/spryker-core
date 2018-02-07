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
     * - Adds one product to the customer permission list.
     *
     * @api
     *
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function saveCustomerProductPermission(int $idCustomer, int $idProductAbstract);

    /**
     * Specification:
     * - Adds new products to the customer permission list, if they are no assigned yet.
     *
     * @api
     *
     * @param int $idCustomer
     * @param array $idProductAbstracts
     *
     * @return void
     */
    public function saveCustomerProductPermissions(int $idCustomer, array $idProductAbstracts);

    /**
     * Specification:
     * - Deletes one product from the customer permission list.
     *
     * @api
     *
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deleteCustomerProductPermission(int $idCustomer, int $idProductAbstract);

    /**
     * Specification:
     * - Deletes all products from the customer permission list.
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return void
     */
    public function deleteAllCustomerProductPermissions(int $idCustomer);

    /**
     * Specification:
     * - Deletes specified products from the customer permission list.
     *
     * @api
     *
     * @param int $idCustomer
     * @param array $idProductAbstracts
     *
     * @return void
     */
    public function deleteCustomerProductPermissions(int $idCustomer, array $idProductAbstracts);

    /**
     * Specification:
     * - Checks added to cart products for current customer permissions.
     * - Returns pre-check transfer with error messages (in negative case).
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
