<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Braintree;

use Codeception\Actor;
use Codeception\Scenario;
use SprykerTest\Yves\Braintree\PageObject\CheckoutPage;
use SprykerTest\Yves\Braintree\PageObject\ProductDetailPage;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class BraintreePresentationTester extends Actor
{
    use _generated\BraintreePresentationTesterActions;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->amYves();
    }

    /**
     * @return void
     */
    public function checkoutWithCreditCardAsGuest()
    {
        $i = $this;

        $i->startCheckout();
        $i->submitGuestCustomerForm();
        $i->submitAddressForm();
        $i->selectShipmentMethod();
        $i->seeBraintreePaymentMethods();
        $i->submitCreditCardForm();
        $i->submitOrder();
    }

    /**
     * @return void
     */
    public function checkoutWithPayPalAsGuest()
    {
        $i = $this;

        $i->startCheckout();
        $i->submitGuestCustomerForm();
        $i->submitAddressForm();
        $i->selectShipmentMethod();
        $i->seeBraintreePaymentMethods();
        $i->submitPayPalForm();
        $i->submitOrder();
    }

    /**
     * @param string $productIdentifier Url to product e.g. /en/samsung-123
     *
     * @return void
     */
    public function addToCart($productIdentifier)
    {
        $i = $this;
        $i->amOnPage($productIdentifier);
        $i->click(ProductDetailPage::BUTTON_ADD_TO_CART);
        $i->wait(5);
    }

    /**
     * @return void
     */
    public function startCheckout()
    {
        $i = $this;
        $i->click(CheckoutPage::BUTTON_CHECKOUT);
        $i->wait(5);
    }

    /**
     * @return void
     */
    public function submitGuestCustomerForm()
    {
        $i = $this;
        $i->amOnPage(CheckoutPage::URL_CUSTOMER);
        $i->waitForElement(['id' => 'guest'], 20);
        $i->checkOption(['id' => 'guest']);
        $i->submitForm('form[name=guestForm]', [
            'guestForm[customer][salutation]' => 'Mr',
            'guestForm[customer][first_name]' => 'Tester',
            'guestForm[customer][last_name]' => 'Tester',
            'guestForm[customer][email]' => 'tester@spryker.com',
            'guestForm[customer][accept_terms]' => true,
        ]);
        $i->wait(5);
        $i->canSeeCurrentUrlEquals(CheckoutPage::URL_ADDRESS);
    }

    /**
     * @return void
     */
    public function submitAddressForm()
    {
        $i = $this;
        $i->amOnPage(CheckoutPage::URL_ADDRESS);
        $i->submitForm('form[name=addressesForm]', [
            'addressesForm[shippingAddress][salutation]' => 'Mr',
            'addressesForm[shippingAddress][first_name]' => 'Tester',
            'addressesForm[shippingAddress][last_name]' => 'Tester',
            'addressesForm[shippingAddress][address1]' => 'Spryker Street',
            'addressesForm[shippingAddress][address2]' => '1',
            'addressesForm[shippingAddress][zip_code]' => '12347',
            'addressesForm[shippingAddress][city]' => 'Berlin',
            'addressesForm[billingSameAsShipping]' => true,
        ]);
        $i->canSeeCurrentUrlEquals(CheckoutPage::URL_SHIPMENT);
    }

    /**
     * @return void
     */
    public function selectShipmentMethod()
    {
        $i = $this;

        $i->amOnPage(CheckoutPage::URL_SHIPMENT);
        $i->submitForm('form[name=shipmentForm]', [
            'shipmentForm[idShipmentMethod]' => '1',
        ]);
        $i->wait(5);
        $i->canSeeCurrentUrlEquals(CheckoutPage::URL_PAYMENT);
    }

    /**
     * @return void
     */
    public function seeBraintreePaymentMethods()
    {
        $i = $this;
        $i->amOnPage(CheckoutPage::URL_PAYMENT);
        $i->see('BraintreeCreditCard');
        $i->see('BraintreePayPal');
    }

    /**
     * @return void
     */
    public function submitCreditCardForm()
    {
        $i = $this;
        $i->amOnPage(CheckoutPage::URL_PAYMENT);

        $i->click(['id' => 'paymentForm_paymentSelection_0']);

        $i->switchToIFrame('braintree-hosted-field-number');

        $i->fillField('#credit-card-number', '4111111111111111');
        $i->switchToIFrame();

        $i->switchToIFrame('braintree-hosted-field-cvv');
        $i->fillField('#cvv', '123');
        $i->switchToIFrame();

        $expirationYear = date('y') + 2;
        $i->switchToIFrame('braintree-hosted-field-expirationDate');
        $i->fillField('#expiration', '10/' . $expirationYear);
        $i->fillField('#expiration', '10/' . $expirationYear);
        $i->switchToIFrame();

        $i->click('Go to Summary');
        $i->wait(5);
        $i->canSeeCurrentUrlEquals(CheckoutPage::URL_SUMMARY);
    }

    /**
     * PayPal checkout will open a popup window where customer needs to login.
     * The main windows name is "PPFrameRedirect" inside of this window there will
     * be a iFrame "injectedUl" which contains the login form.
     *
     * https://developers.braintreepayments.com/guides/paypal/testing-go-live/php
     *
     * @return void
     */
    public function submitPayPalForm()
    {
        $i = $this;
        $i->amOnPage(CheckoutPage::URL_PAYMENT);
        $i->click(['id' => 'paymentForm_paymentSelection_1']);
        $i->click(['id' => 'braintree-paypal-button']);

        $this->fillOutPayPalForm();

        $i->seeElement(['id' => 'braintree-paypal-loggedin']);
        $i->click('Go to Summary');
        $i->wait(5);
        $i->canSeeCurrentUrlEquals(CheckoutPage::URL_SUMMARY);
    }

    /**
     * @return void
     */
    protected function fillOutPayPalForm()
    {
        $i = $this;
        $i->switchToWindow('PPFrameRedirect');
        $i->wait(10);
        $i->switchToIFrame('injectedUl');
        $i->fillField(['id' => 'email'], 'payment.test-123@spryker.com');
        $i->fillField(['id' => 'password'], 'spryker123');
        $i->click(['id' => 'btnLogin']);
        $i->wait(10);
        $i->waitForElement(['id' => 'confirmButtonTop'], 30);
        $i->click(['id' => 'confirmButtonTop']);
        $i->switchToWindow();
        $i->wait(10);
    }

    /**
     * @return void
     */
    public function submitOrder()
    {
        $i = $this;
        $i->amOnPage(CheckoutPage::URL_SUMMARY);
        $i->click('Submit your order');

        $i->canSeeCurrentUrlEquals(CheckoutPage::URL_SUCCESS);
    }
}
