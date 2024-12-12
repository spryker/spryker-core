<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentApp\Communication\Controller;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\PaymentApp\Business\Exception\PaymentAppEndpointNotFoundException;
use Spryker\Zed\PaymentApp\Communication\Controller\GatewayController;
use SprykerTest\Zed\PaymentApp\PaymentAppCommunicationTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentApp
 * @group Communication
 * @group Controller
 * @group GatewayControllerCustomerTest
 * Add your own group annotations below this line
 */
class GatewayControllerCustomerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PaymentApp\PaymentAppCommunicationTester
     */
    protected PaymentAppCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGivenThePassedPaymentMethodDoesNotExistsWhenTheGetCustomerMethodIsCalledThenAFailedResponseIsReturned(): void
    {
        // Arrange
        $paymentCustomerRequestTransfer = $this->tester->havePaymentCustomerRequestTransfer();

        // Act
        $paymentCustomerResponseTransfer = $this->tester->getGatewayController()->getCustomerAction($paymentCustomerRequestTransfer);

        // Assert
        $this->assertFalse($paymentCustomerResponseTransfer->getIsSuccessful());
        $this->assertSame('Payment method not found', $paymentCustomerResponseTransfer->getError());
    }

    /**
     * @return void
     */
    public function testGivenTheRequestCanBeMadeWhenTheResponseStatusCodeIsNot200ThenAFailedResponseIsReturned(): void
    {
        // Arrange
        $paymentMethodTransfer = $this->tester->havePaymentMethodWithPaymentMethodAppConfigurationForCustomerEndpointPersisted();
        $paymentCustomerRequestTransfer = $this->tester->havePaymentCustomerRequestTransfer($paymentMethodTransfer);

        $this->tester->mockKernelAppFacadeResponse(Response::HTTP_BAD_REQUEST, ['error' => 'something went wrong on th App side']);

        // Act
        $paymentCustomerResponseTransfer = $this->tester->getGatewayController()->getCustomerAction($paymentCustomerRequestTransfer);

        // Assert
        $this->assertFalse($paymentCustomerResponseTransfer->getIsSuccessful());
        $this->assertSame('something went wrong on th App side', $paymentCustomerResponseTransfer->getError());
    }

    /**
     * @return void
     */
    public function testGivenTheRequestCanBeMadeWhenTheResponseDoesNotContainValidJsonThenAFailedResponseIsReturned(): void
    {
        // Arrange
        $paymentMethodTransfer = $this->tester->havePaymentMethodWithPaymentMethodAppConfigurationForCustomerEndpointPersisted();
        $paymentCustomerRequestTransfer = $this->tester->havePaymentCustomerRequestTransfer($paymentMethodTransfer);

        $this->tester->mockKernelAppFacadeResponse(Response::HTTP_BAD_REQUEST, 'something went wrong on th App side');

        // Act
        $paymentCustomerResponseTransfer = $this->tester->getGatewayController()->getCustomerAction($paymentCustomerRequestTransfer);

        // Assert
        $this->assertFalse($paymentCustomerResponseTransfer->getIsSuccessful());
        $this->assertSame('something went wrong on th App side', $paymentCustomerResponseTransfer->getError());
    }

    /**
     * @return void
     */
    public function testGivenTheRequestCanBeMadeWhenTheResponseIsSuccessfulThenASuccessfulResponseIsReturned(): void
    {
        // Arrange
        $paymentMethodTransfer = $this->tester->havePaymentMethodWithPaymentMethodAppConfigurationForCustomerEndpointPersisted();
        $paymentCustomerRequestTransfer = $this->tester->havePaymentCustomerRequestTransfer($paymentMethodTransfer);

        $customerData = [
            'customer' => [
                'firstName' => 'firstName',
                'lastName' => 'lastName',
                'shippingAddress' => [
                    'firstName' => 'firstName',
                    'lastName' => 'lastName',
                    'address1' => 'Street 23',
                    'city' => 'Berlin',
                    'zipCode' => '12347',
                ],
                'billingAddress' => [
                    'firstName' => 'firstName',
                    'lastName' => 'lastName',
                    'address1' => 'Street 23',
                    'city' => 'Berlin',
                    'zipCode' => '12347',
                ],
            ],
        ];

        $this->tester->mockKernelAppFacadeResponse(Response::HTTP_OK, $customerData);

        // Act
        $paymentCustomerResponseTransfer = $this->tester->getGatewayController()->getCustomerAction($paymentCustomerRequestTransfer);

        // Assert
        $this->assertTrue($paymentCustomerResponseTransfer->getIsSuccessful());

        $customerTransfer = $paymentCustomerResponseTransfer->getCustomer();

        $this->assertInstanceOf(AddressTransfer::class, $customerTransfer->getShippingAddress()[0]);
        $this->assertInstanceOf(AddressTransfer::class, $customerTransfer->getBillingAddress()[0]);
    }

    /**
     * @return void
     */
    public function testGivenThePassedPaymentMethodInTheRequestDoesNotHaveAGetCustomerEndpointWhenTheGetCustomerMethodIsCalledThenAnExceptionIsThrown(): void
    {
        // Arrange
        $paymentMethodTransfer = $this->tester->havePaymentMethodWithPaymentMethodAppConfigurationPersisted(); // This doesn't have the customer endpoint configured
        $paymentCustomerRequestTransfer = $this->tester->havePaymentCustomerRequestTransfer($paymentMethodTransfer);

        // Expect
        $this->expectException(PaymentAppEndpointNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Could not find an endpoint for getting customers data of the payment method "%s". A reason for this could be that the Payment Apps configuration was not updated or not synced back.', $paymentMethodTransfer->getPaymentMethodKey()));

        // Act
        $this->tester->getFacade()->getCustomer($paymentCustomerRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGetCustomerThrowsAnExceptionWhenThePaymentIsNotSet(): void
    {
        // Arrange
        $paymentCustomerRequestTransfer = new PaymentCustomerRequestTransfer();

        // Expect
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "payment" of transfer `Generated\Shared\Transfer\PaymentCustomerRequestTransfer` is null.');

        // Act
        $gatewayController = new GatewayController();
        $gatewayController->getCustomerAction($paymentCustomerRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGetCustomerThrowsAnExceptionWhenThePaymentProviderNameIsNotSet(): void
    {
        // Arrange
        $paymentTransfer = new PaymentTransfer();

        $paymentCustomerRequestTransfer = new PaymentCustomerRequestTransfer();
        $paymentCustomerRequestTransfer
            ->setPayment($paymentTransfer);

        // Expect
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "paymentProviderName" of transfer `Generated\Shared\Transfer\PaymentTransfer` is null.');

        // Act
        $gatewayController = new GatewayController();
        $gatewayController->getCustomerAction($paymentCustomerRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGetCustomerThrowsAnExceptionWhenThePaymentMethodNameIsNotSet(): void
    {
        // Arrange
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer
            ->setPaymentProviderName('test-payment-provider');

        $paymentCustomerRequestTransfer = new PaymentCustomerRequestTransfer();
        $paymentCustomerRequestTransfer
            ->setPayment($paymentTransfer);

        // Expect
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "paymentMethodName" of transfer `Generated\Shared\Transfer\PaymentTransfer` is null.');

        // Act
        $gatewayController = new GatewayController();
        $gatewayController->getCustomerAction($paymentCustomerRequestTransfer);
    }
}
