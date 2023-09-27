<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ServicePointCart;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
 * @method \Spryker\Zed\ServicePointCart\Business\ServicePointCartFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ServicePointCart\PHPMD)
 */
class ServicePointCartBusinessTester extends Actor
{
    use _generated\ServicePointCartBusinessTesterActions;

    /**
     * @uses \Spryker\Zed\ServicePointCart\Business\Validator\QuoteItemServicePointValidator::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_CART_CHECKOUT_ERROR
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_CART_CHECKOUT_ERROR = 'service_point_cart.checkout.validation.error';

    /**
     * @uses \Spryker\Zed\ServicePointCart\Business\Validator\QuoteItemServicePointValidator::GLOSSARY_KEY_PARAMETER_SERVICE_POINT_UUID
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_SERVICE_POINT_UUID = '%uuid%';

    /**
     * @uses \Spryker\Zed\ServicePointCart\Business\Validator\QuoteItemServicePointValidator::GLOSSARY_KEY_PARAMETER_STORE_NAME
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_STORE_NAME = '%store_name%';

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function createStoreRelationTransfer(StoreTransfer $storeTransfer): StoreRelationTransfer
    {
        return (new StoreRelationBuilder([
            StoreRelationTransfer::STORES => (new ArrayObject([[
                StoreTransfer::NAME => $storeTransfer->getName(),
                StoreTransfer::ID_STORE => $storeTransfer->getIdStore(),
            ]])),
        ]))->build();
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     * @param string $servicePointUuid
     * @param string $storeName
     *
     * @return void
     */
    public function assertCheckoutErrorTransfer(
        CheckoutErrorTransfer $checkoutErrorTransfer,
        string $servicePointUuid,
        string $storeName
    ): void {
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_CART_CHECKOUT_ERROR, $checkoutErrorTransfer->getMessage());
        $this->assertArrayHasKey(static::GLOSSARY_KEY_PARAMETER_SERVICE_POINT_UUID, $checkoutErrorTransfer->getParameters());
        $this->assertSame($servicePointUuid, $checkoutErrorTransfer->getParameters()[static::GLOSSARY_KEY_PARAMETER_SERVICE_POINT_UUID]);
        $this->assertArrayHasKey(static::GLOSSARY_KEY_PARAMETER_STORE_NAME, $checkoutErrorTransfer->getParameters());
        $this->assertSame($storeName, $checkoutErrorTransfer->getParameters()[static::GLOSSARY_KEY_PARAMETER_STORE_NAME]);
    }
}
