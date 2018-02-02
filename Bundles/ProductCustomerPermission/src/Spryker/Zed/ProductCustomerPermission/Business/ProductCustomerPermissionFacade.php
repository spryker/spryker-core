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
     * @param int $idCustomer
     * @param int $productId
     *
     * @return void
     */
    public function saveCustomerProductPermission(int $idCustomer, int $productId)
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->savePermission($idCustomer, $productId);
    }

    /**
     * @inheritdoc
     *
     * @api
     *
     * @param int $idCustomer
     * @param array $productIds
     *
     * @return void
     */
    public function saveCustomerProductPermissions(int $idCustomer, array $productIds)
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->savePermissions($idCustomer, $productIds);
    }

    /**
     * @inheritdoc
     *
     * @api
     *
     * @param int $idCustomer
     * @param int $productId
     *
     * @return void
     */
    public function deleteCustomerProductPermission(int $idCustomer, int $productId)
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->deletePermission($idCustomer, $productId);
    }

    /**
     * @inheritdoc
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return void
     */
    public function deleteAllCustomerProductPermissions(int $idCustomer)
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->deleteAllPermissions($idCustomer);
    }

    /**
     * @inheritdoc
     *
     * @api
     *
     * @param int $idCustomer
     * @param array $productIds
     *
     * @return void
     */
    public function deleteCustomerProductPermissions(int $idCustomer, array $productIds)
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->deletePermissions($idCustomer, $productIds);
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
