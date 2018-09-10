<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutPermissionConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @example
 *
 * @method \Spryker\Zed\CheckoutPermissionConnector\Communication\CheckoutPermissionCommunicationFactory getFactory()
 */
class CheckoutPlaceOrderGrandTotalXPermissionPlugin extends AbstractPlugin implements CheckoutPreConditionInterface, ExecutablePermissionPluginInterface
{
    const CONFIG_FIELD_AMOUNT = 'CONFIG_FIELD_AMOUNT';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $can = $this->getFactory()->getPermissionFacade()->can(
            $this->getKey(),
            $quoteTransfer->getCustomer()->getIdCustomer(),
            $quoteTransfer->getTotals()->getGrandTotal()
        );

        if ($can) {
            return true;
        }

        $this->addErrorToCheckoutResponse($checkoutResponseTransfer);
        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function addErrorToCheckoutResponse(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $checkoutErrorTransfer = (new CheckoutErrorTransfer())
            ->setMessage('Access denied')
            ->setErrorCode(403);

        $checkoutResponseTransfer
            ->addError($checkoutErrorTransfer);
    }

    /**
     * @api
     *
     * @param array $configuration
     * @param int|null $centAmount
     *
     * @return bool
     */
    public function can(array $configuration, $centAmount = null)
    {
        if ($centAmount === null) {
            return false;
        }

        if ($centAmount > $configuration[static::CONFIG_FIELD_AMOUNT]) {
            return false;
        }

        return true;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getConfigurationSignature()
    {
        return [
            static::CONFIG_FIELD_AMOUNT => ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_INT,
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return 'permission.allow.checkout.placeOrder.grandTotalX';
    }
}
