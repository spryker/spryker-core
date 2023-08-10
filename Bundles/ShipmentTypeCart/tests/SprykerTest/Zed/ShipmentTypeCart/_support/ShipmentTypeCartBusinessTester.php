<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeCart;

use Codeception\Actor;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ShipmentTypeCart\Business\ShipmentTypeCartFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentTypeCartBusinessTester extends Actor
{
    use _generated\ShipmentTypeCartBusinessTesterActions;

    /**
     * @uses \Spryker\Zed\ShipmentTypeCart\Business\Validator\MultiShipmentShipmentTypeCheckoutValidator::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_CART_CHECKOUT_ERROR
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_CART_CHECKOUT_ERROR = 'shipment_type_cart.checkout.validation.error';

    /**
     * @uses \Spryker\Zed\ShipmentTypeCart\Business\Validator\MultiShipmentShipmentTypeCheckoutValidator::ERROR_MESSAGE_PARAMETER_NAME
     *
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_NAME = '%name%';

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return void
     */
    public function assertCheckoutErrorTransfer(CheckoutErrorTransfer $checkoutErrorTransfer, ShipmentTypeTransfer $shipmentTypeTransfer): void
    {
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_CART_CHECKOUT_ERROR, $checkoutErrorTransfer->getMessage());
        $this->assertArrayHasKey(static::ERROR_MESSAGE_PARAMETER_NAME, $checkoutErrorTransfer->getParameters());
        $this->assertSame($shipmentTypeTransfer->getNameOrFail(), $checkoutErrorTransfer->getParameters()[static::ERROR_MESSAGE_PARAMETER_NAME]);
    }
}
