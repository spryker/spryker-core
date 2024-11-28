<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentApp\Business\Facade;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\EndpointTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentMethodAppConfigurationTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\KernelApp\Business\KernelAppFacadeInterface;
use Spryker\Zed\Payment\Business\PaymentFacade;
use Spryker\Zed\PaymentApp\Business\Exception\PaymentAppEndpointNotFoundException;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToKernelAppFacadeBridge;
use Spryker\Zed\PaymentApp\PaymentAppConfig;
use Spryker\Zed\PaymentApp\PaymentAppDependencyProvider;
use SprykerTest\Zed\PaymentApp\PaymentAppBusinessTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentApp
 * @group Business
 * @group Facade
 * @group Facade
 * @group PaymentAppFacadeGetCustomerTest
 * Add your own group annotations below this line
 */
class PaymentAppFacadeGetCustomerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PaymentApp\PaymentAppBusinessTester
     */
    protected PaymentAppBusinessTester $tester;

    /**
     * @return void
     */
    public function testGivenThePassedPaymentMethodDoesNotExistsWhenTheGetCustomerMethodIsCalledThenAFailedResponseIsReturned(): void
    {
        // Arrange
        $paymentMethodName = 'method-' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'provider-' . Uuid::uuid4()->toString();

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer
            ->setPaymentMethodName($paymentMethodName)
            ->setPaymentProviderName($paymentProviderKey);

        $paymentCustomerRequestTransfer = new PaymentCustomerRequestTransfer();
        $paymentCustomerRequestTransfer
            ->setPayment($paymentTransfer)
            ->setCustomerPaymentServiceProviderData([
                'foo' => 'bar',
            ]);

        // Act
        $paymentCustomerResponseTransfer = $this->tester->getFacade()->getCustomer($paymentCustomerRequestTransfer);

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
        $paymentMethodName = 'method-' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'provider-' . Uuid::uuid4()->toString();

        $this->tester->havePaymentMethodWithPaymentProviderPersisted([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey,
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => (new PaymentFacade())->generatePaymentMethodKey($paymentProviderKey, $paymentMethodName),
            PaymentMethodTransfer::NAME => $paymentMethodName,
            PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION => [
                PaymentMethodAppConfigurationTransfer::BASE_URL => 'http://foo.bar',
                PaymentMethodAppConfigurationTransfer::ENDPOINTS => [
                    [
                        EndpointTransfer::NAME => PaymentAppConfig::PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_CUSTOMER,
                        EndpointTransfer::PATH => '/customer',
                    ],
                ],
            ],
        ]);

        // Mock the KernelApp response
        $kernelAppFacadeMock = Stub::makeEmpty(KernelAppFacadeInterface::class, [
            'makeRequest' => function () {
                $acpHttpResponseTransfer = new AcpHttpResponseTransfer();
                $acpHttpResponseTransfer
                    ->setHttpStatusCode(Response::HTTP_BAD_REQUEST)
                    ->setContent('{"error": "something went wrong on th App side"}');

                return $acpHttpResponseTransfer;
            },
        ]);

        $this->tester->setDependency(PaymentAppDependencyProvider::FACADE_KERNEL_APP, new PaymentAppToKernelAppFacadeBridge($kernelAppFacadeMock));

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer
            ->setPaymentMethodName($paymentMethodName)
            ->setPaymentProviderName($paymentProviderKey);

        $paymentCustomerRequestTransfer = new PaymentCustomerRequestTransfer();
        $paymentCustomerRequestTransfer
            ->setPayment($paymentTransfer)
            ->setCustomerPaymentServiceProviderData([
                'foo' => 'bar',
            ]);

        // Act
        $paymentCustomerResponseTransfer = $this->tester->getFacade()->getCustomer($paymentCustomerRequestTransfer);

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
        $paymentMethodName = 'method-' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'provider-' . Uuid::uuid4()->toString();

        $this->tester->havePaymentMethodWithPaymentProviderPersisted([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey,
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => (new PaymentFacade())->generatePaymentMethodKey($paymentProviderKey, $paymentMethodName),
            PaymentMethodTransfer::NAME => $paymentMethodName,
            PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION => [
                PaymentMethodAppConfigurationTransfer::BASE_URL => 'http://foo.bar',
                PaymentMethodAppConfigurationTransfer::ENDPOINTS => [
                    [
                        EndpointTransfer::NAME => PaymentAppConfig::PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_CUSTOMER,
                        EndpointTransfer::PATH => '/customer',
                    ],
                ],
            ],
        ]);

        // Mock the KernelApp response
        $kernelAppFacadeMock = Stub::makeEmpty(KernelAppFacadeInterface::class, [
            'makeRequest' => function () {
                $acpHttpResponseTransfer = new AcpHttpResponseTransfer();
                $acpHttpResponseTransfer
                    ->setHttpStatusCode(Response::HTTP_BAD_REQUEST)
                    ->setContent('something went wrong on th App side');

                return $acpHttpResponseTransfer;
            },
        ]);

        $this->tester->setDependency(PaymentAppDependencyProvider::FACADE_KERNEL_APP, new PaymentAppToKernelAppFacadeBridge($kernelAppFacadeMock));

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer
            ->setPaymentMethodName($paymentMethodName)
            ->setPaymentProviderName($paymentProviderKey);

        $paymentCustomerRequestTransfer = new PaymentCustomerRequestTransfer();
        $paymentCustomerRequestTransfer
            ->setPayment($paymentTransfer)
            ->setCustomerPaymentServiceProviderData([
                'foo' => 'bar',
            ]);

        // Act
        $paymentCustomerResponseTransfer = $this->tester->getFacade()->getCustomer($paymentCustomerRequestTransfer);

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
        $paymentMethodName = 'method-' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'provider-' . Uuid::uuid4()->toString();

        $this->tester->havePaymentMethodWithPaymentProviderPersisted([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey,
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => (new PaymentFacade())->generatePaymentMethodKey($paymentProviderKey, $paymentMethodName),
            PaymentMethodTransfer::NAME => $paymentMethodName,
            PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION => [
                PaymentMethodAppConfigurationTransfer::BASE_URL => 'http://foo.bar',
                PaymentMethodAppConfigurationTransfer::ENDPOINTS => [
                    [
                        EndpointTransfer::NAME => PaymentAppConfig::PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_CUSTOMER,
                        EndpointTransfer::PATH => '/customer',
                    ],
                ],
            ],
        ]);

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

        // Mock the KernelApp response
        $kernelAppFacadeMock = Stub::makeEmpty(KernelAppFacadeInterface::class, [
            'makeRequest' => function () use ($customerData) {
                $acpHttpResponseTransfer = new AcpHttpResponseTransfer();
                $acpHttpResponseTransfer
                    ->setHttpStatusCode(Response::HTTP_OK)
                    ->setContent(json_encode($customerData));

                return $acpHttpResponseTransfer;
            },
        ]);

        $this->tester->setDependency(PaymentAppDependencyProvider::FACADE_KERNEL_APP, new PaymentAppToKernelAppFacadeBridge($kernelAppFacadeMock));

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer
            ->setPaymentMethodName($paymentMethodName)
            ->setPaymentProviderName($paymentProviderKey);

        $paymentCustomerRequestTransfer = new PaymentCustomerRequestTransfer();
        $paymentCustomerRequestTransfer
            ->setPayment($paymentTransfer)
            ->setCustomerPaymentServiceProviderData([
                'foo' => 'bar',
            ]);

        // Act
        $paymentCustomerResponseTransfer = $this->tester->getFacade()->getCustomer($paymentCustomerRequestTransfer);

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
        $paymentMethodName = 'method-' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'provider-' . Uuid::uuid4()->toString();

        $paymentMethodKey = sprintf('%s-%s', $paymentProviderKey, $paymentMethodName);

        $this->tester->havePaymentMethodWithPaymentProviderPersisted([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey,
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => (new PaymentFacade())->generatePaymentMethodKey($paymentProviderKey, $paymentMethodName),
            PaymentMethodTransfer::NAME => $paymentMethodName,
            PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION => [
                EndpointTransfer::NAME => 'foo',
            ],
        ]);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer
            ->setPaymentMethodName($paymentMethodName)
            ->setPaymentProviderName($paymentProviderKey);

        $paymentCustomerRequestTransfer = new PaymentCustomerRequestTransfer();
        $paymentCustomerRequestTransfer
            ->setPayment($paymentTransfer)
            ->setCustomerPaymentServiceProviderData([
                'foo' => 'bar',
            ]);

        // Expect
        $this->expectException(PaymentAppEndpointNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Could not find an endpoint for getting customers data of the payment method "%s". A reason for this could be that the Payment Apps configuration was not updated or not synced back.', $paymentMethodKey));

        // Act
        $this->tester->getFacade()->getCustomer($paymentCustomerRequestTransfer);
    }
}
