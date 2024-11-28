<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PaymentsRestApi\Controller;

use ArrayObject;
use Codeception\Stub;
use Codeception\Test\Unit;
use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Generated\Shared\Transfer\RestPaymentCustomersRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentCustomersResponseAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentTransfer;
use Spryker\Client\PaymentApp\PaymentAppClient;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\PaymentsRestApi\Controller\PaymentCustomersResourceController;
use Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentAppClientBridge;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiDependencyProvider;
use SprykerTest\Glue\PaymentsRestApi\PaymentsRestApiControllerTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PaymentsRestApi
 * @group Controller
 * @group PaymentCustomersResourceControllerTest
 * Add your own group annotations below this line
 */
class PaymentCustomersResourceControllerTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var \SprykerTest\Glue\PaymentsRestApi\PaymentsRestApiControllerTester
     */
    protected PaymentsRestApiControllerTester $tester;

    /**
     * @return void
     */
    public function _before(): void
    {
        parent::_before();

        $this->tester->getContainer()->set(static::SERVICE_RESOURCE_BUILDER, new RestResourceBuilder());
    }

    /**
     * @return void
     */
    public function testRequestCustomerReturnsCreatedHttpResponseWithCustomerData(): void
    {
        // Arrange
        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setFirstName('Spencor')
            ->setLastName('Hopkins')
            ->setAddress1('Julie-Wofthorn Str. 1')
            ->setCity('Berlin');

        $customerTransfer = new CustomerTransfer();
        $customerTransfer
            ->setEmail('spencor.hopkins@spryker.com')
            ->setShippingAddress(new ArrayObject($addressTransfer))
            ->setBillingAddress(new ArrayObject($addressTransfer));

        $paymentCustomerResponseTransfer = new PaymentCustomerResponseTransfer();
        $paymentCustomerResponseTransfer
            ->setIsSuccessful(true)
            ->setCustomer($customerTransfer);

        $paymentAppClientStub = Stub::make(PaymentAppClient::class, [
            'getCustomer' => $paymentCustomerResponseTransfer,
        ]);

        $paymentsRestApiToPaymentAppClientBridge = new PaymentsRestApiToPaymentAppClientBridge($paymentAppClientStub);
        $this->tester->setDependency(PaymentsRestApiDependencyProvider::CLIENT_PAYMENT_APP, $paymentsRestApiToPaymentAppClientBridge);

        $restPaymentTransfer = new RestPaymentTransfer();
        $restPaymentTransfer
            ->setPaymentMethodName('foo')
            ->setPaymentProviderName('bar');

        $restPaymentCustomersRequestAttributesTransfer = new RestPaymentCustomersRequestAttributesTransfer();
        $restPaymentCustomersRequestAttributesTransfer
            ->setPayment($restPaymentTransfer)
            ->setCustomerPaymentServiceProviderData([
                'foo' => 'bar',
            ]);

        $restRequestStub = Stub::makeEmpty(RestRequestInterface::class);

        //Act
        $paymentCustomersResourceController = new PaymentCustomersResourceController();

        $restResponse = $paymentCustomersResourceController->postAction(
            $restRequestStub,
            $restPaymentCustomersRequestAttributesTransfer,
        );

        //Assert
        $this->assertCount(0, $restResponse->getErrors());
        $this->assertCount(1, $restResponse->getResources());

        $restResource = $restResponse->getResources()[0];

        /** @var \Generated\Shared\Transfer\PaymentCustomerResponseTransfer $attributes */
        $attributes = $restResource->getAttributes();

        $this->assertInstanceOf(RestPaymentCustomersResponseAttributesTransfer::class, $attributes);
    }

    /**
     * @return void
     */
    public function testRequestCustomerReturnsUnprocessableHttpResponseWhenPaymentMethodWasNotFound(): void
    {
        // Arrange
        $paymentCustomerResponseTransfer = new PaymentCustomerResponseTransfer();
        $paymentCustomerResponseTransfer
            ->setIsSuccessful(false)
            ->setError('Could not find a payment method matching your request.');

        $paymentAppClientStub = Stub::make(PaymentAppClient::class, [
            'getCustomer' => $paymentCustomerResponseTransfer,
        ]);

        $paymentsRestApiToPaymentAppClientBridge = new PaymentsRestApiToPaymentAppClientBridge($paymentAppClientStub);
        $this->tester->setDependency(PaymentsRestApiDependencyProvider::CLIENT_PAYMENT_APP, $paymentsRestApiToPaymentAppClientBridge);

        $paymentCustomersResourceController = new PaymentCustomersResourceController();

        $restPaymentTransfer = new RestPaymentTransfer();
        $restPaymentTransfer
            ->setPaymentMethodName('foo')
            ->setPaymentProviderName('bar');

        $restPaymentCustomersRequestAttributesTransfer = new RestPaymentCustomersRequestAttributesTransfer();
        $restPaymentCustomersRequestAttributesTransfer
            ->setPayment($restPaymentTransfer)
            ->setCustomerPaymentServiceProviderData([
                'foo' => 'bar',
            ]);

        $restRequestStub = Stub::makeEmpty(RestRequestInterface::class);

        //Act
        $restResponse = $paymentCustomersResourceController->postAction(
            $restRequestStub,
            $restPaymentCustomersRequestAttributesTransfer,
        );

        //Assert
        $this->assertCount(1, $restResponse->getErrors());

        $restErrorMessageTransfer = $restResponse->getErrors()[0];

        $this->assertSame(HttpCode::UNPROCESSABLE_ENTITY, $restErrorMessageTransfer->getStatus());
    }
}
