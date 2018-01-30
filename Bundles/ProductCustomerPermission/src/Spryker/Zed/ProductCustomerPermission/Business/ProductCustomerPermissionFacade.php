<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCustomerPermission\Business\ProductCustomerPermissionBusinessFactory getFactory()
 */
class ProductCustomerPermissionFacade extends AbstractFacade implements ProductCustomerPermissionFacadeInterface
{
    /**
     * @inheritdoc
     *
     * @api
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function saveCustomerProductPermission(int $customerId, int $productId)
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->savePermission($customerId, $productId);
    }

    /**
     * @inheritdoc
     *
     * @api
     *
     * @param int $customerId
     * @param array $productIds
     *
     * @return void
     */
    public function saveCustomerProductPermissions(int $customerId, array $productIds)
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->savePermissions($customerId, $productIds);
    }

    /**
     * @inheritdoc
     *
     * @api
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function deleteCustomerProductPermission(int $customerId, int $productId)
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->deletePermission($customerId, $productId);
    }

    /**
     * @inheritdoc
     *
     * @api
     *
     * @param int $customerId
     *
     * @return void
     */
    public function deleteCustomerProductPermissions(int $customerId)
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->deletePermissions($customerId);
    }

    /**
     * @inheritdoc
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     **/
    public function checkPermissions(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createCartValidator()
            ->checkPermissions($cartChangeTransfer);
    }

    /**
     * @inheritdoc
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkoutPreCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        return $this->getFactory()
            ->createCheckoutPreConditionChecker()
            ->checkPreCondition($quoteTransfer, $checkoutResponseTransfer);
    }
}
