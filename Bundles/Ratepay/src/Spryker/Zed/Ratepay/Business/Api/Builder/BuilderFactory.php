<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Builder;

use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 */
class BuilderFactory
{
    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(RatepayRequestTransfer $requestTransfer)
    {
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\Customer
     */
    public function createCustomer()
    {
        return new Customer(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\Address
     */
    public function createCustomerAddress()
    {
        return new Address(
            $this->requestTransfer,
            Constants::REQUEST_MODEL_ADDRESS_TYPE_BILLING
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\BankAccount
     */
    public function createBankAccount()
    {
        return new BankAccount(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\Head
     */
    public function createHead()
    {
        return new Head(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\Payment
     */
    public function createPayment()
    {
        return new Payment(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\ShoppingBasket
     */
    public function createShoppingBasket()
    {
        return new ShoppingBasket(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\ShoppingBasketItem
     */
    public function createShoppingBasketItem()
    {
        return new ShoppingBasketItem(
            $this->requestTransfer,
            0
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\InstallmentCalculation
     */
    public function createInstallmentCalculation()
    {
        return new InstallmentCalculation(
            $this->requestTransfer
        );
    }
}
