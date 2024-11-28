<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PaymentsRestApi\Controller;

use Codeception\Stub;
use Codeception\Test\Unit;
use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\RestPaymentTransfer;
use Generated\Shared\Transfer\RestPreOrderPaymentCancellationRequestAttributesTransfer;
use Spryker\Client\Payment\PaymentClient;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\PaymentsRestApi\Controller\PreOrderPaymentCancellationsResourceController;
use Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentClientBridge;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiDependencyProvider;
use SprykerTest\Glue\PaymentsRestApi\PaymentsRestApiControllerTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PaymentsRestApi
 * @group Controller
 * @group CancelPreOrderPaymentsResourceControllerTest
 * Add your own group annotations below this line
 */
class CancelPreOrderPaymentsResourceControllerTest extends Unit
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
    public function testRequestInitializePreOrderPaymentReturnsCreatedHttpResponseWithPaymentProviderData(): void
    {
        // Arrange
        $preOrderPaymentResponseTransfer = new PreOrderPaymentResponseTransfer();
        $preOrderPaymentResponseTransfer
            ->setIsSuccessful(true);

        $paymentClientStub = Stub::make(PaymentClient::class, [
            'cancelPreOrderPayment' => $preOrderPaymentResponseTransfer,
        ]);

        $paymentsRestApiToPaymentClientBridge = new PaymentsRestApiToPaymentClientBridge($paymentClientStub);
        $this->tester->setDependency(PaymentsRestApiDependencyProvider::CLIENT_PAYMENT, $paymentsRestApiToPaymentClientBridge);

        $preOrderPaymentCancellationsResourceController = new PreOrderPaymentCancellationsResourceController();

        $restPaymentTransfer = new RestPaymentTransfer();
        $restPaymentTransfer
            ->setPaymentMethodName('foo')
            ->setPaymentProviderName('bar');

        $restPreOrderPaymentCancellationRequestAttributesTransfer = new RestPreOrderPaymentCancellationRequestAttributesTransfer();
        $restPreOrderPaymentCancellationRequestAttributesTransfer
            ->setPayment($restPaymentTransfer)
            ->setPreOrderPaymentData([
                'foo' => 'bar',
            ]);

        $restRequestStub = Stub::makeEmpty(RestRequestInterface::class);

        //Act
        $restResponse = $preOrderPaymentCancellationsResourceController->postAction(
            $restRequestStub,
            $restPreOrderPaymentCancellationRequestAttributesTransfer,
        );

        //Assert
        $this->assertCount(0, $restResponse->getErrors());
        $this->assertCount(1, $restResponse->getResources());

        $restResource = $restResponse->getResources()[0];

        /** @var \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer $attributes */
        $attributes = $restResource->getAttributes();

        $this->assertInstanceOf(PreOrderPaymentResponseTransfer::class, $attributes);
    }

    /**
     * @return void
     */
    public function testRequestInitializePreOrderPaymentReturnsUnprocessableHttpResponseWhenPaymentMethodWasNotFound(): void
    {
        // Arrange
        $preOrderPaymentResponseTransfer = new PreOrderPaymentResponseTransfer();
        $preOrderPaymentResponseTransfer
            ->setIsSuccessful(false)
            ->setError('Could not find a payment method matching your request.');

        $paymentClientStub = Stub::make(PaymentClient::class, [
            'cancelPreOrderPayment' => $preOrderPaymentResponseTransfer,
        ]);

        $paymentsRestApiToPaymentClientBridge = new PaymentsRestApiToPaymentClientBridge($paymentClientStub);
        $this->tester->setDependency(PaymentsRestApiDependencyProvider::CLIENT_PAYMENT, $paymentsRestApiToPaymentClientBridge);

        $preOrderPaymentCancellationsResourceController = new PreOrderPaymentCancellationsResourceController();

        $restPaymentTransfer = new RestPaymentTransfer();
        $restPaymentTransfer
            ->setPaymentMethodName('foo')
            ->setPaymentProviderName('bar');

        $restPreOrderPaymentCancellationRequestAttributesTransfer = new RestPreOrderPaymentCancellationRequestAttributesTransfer();
        $restPreOrderPaymentCancellationRequestAttributesTransfer
            ->setPayment($restPaymentTransfer)
            ->setPreOrderPaymentData([
                'foo' => 'bar',
            ]);

        $restRequestStub = Stub::makeEmpty(RestRequestInterface::class);

        //Act
        $restResponse = $preOrderPaymentCancellationsResourceController->postAction(
            $restRequestStub,
            $restPreOrderPaymentCancellationRequestAttributesTransfer,
        );

        //Assert
        $this->assertCount(1, $restResponse->getErrors());

        $restErrorMessageTransfer = $restResponse->getErrors()[0];

        $this->assertSame(HttpCode::UNPROCESSABLE_ENTITY, $restErrorMessageTransfer->getStatus());
    }
}
