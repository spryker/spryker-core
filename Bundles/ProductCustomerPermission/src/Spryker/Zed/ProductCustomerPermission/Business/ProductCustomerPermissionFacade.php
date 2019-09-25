<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCustomerPermission\Business\ProductCustomerPermissionBusinessFactory getFactory()
 */
class ProductCustomerPermissionFacade extends AbstractFacade implements ProductCustomerPermissionFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function saveCustomerProductPermission(int $idCustomer, int $idProductAbstract): void
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->savePermission($idCustomer, $idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param array $idProductAbstracts
     *
     * @return void
     */
    public function saveCustomerProductPermissions(int $idCustomer, array $idProductAbstracts): void
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->savePermissions($idCustomer, $idProductAbstracts);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deleteCustomerProductPermission(int $idCustomer, int $idProductAbstract): void
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->deletePermission($idCustomer, $idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return void
     */
    public function deleteAllCustomerProductPermissions(int $idCustomer): void
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->deleteAllPermissions($idCustomer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param array $idProductAbstracts
     *
     * @return void
     */
    public function deleteCustomerProductPermissions(int $idCustomer, array $idProductAbstracts): void
    {
        $this->getFactory()
            ->createProductCustomerPermissionSaver()
            ->deletePermissions($idCustomer, $idProductAbstracts);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     **/
    public function checkPermissions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createCartValidator()
            ->checkPermissions($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkoutPreCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getFactory()
            ->createCheckoutPreConditionChecker()
            ->checkPreCondition($quoteTransfer, $checkoutResponseTransfer);
    }
}
