<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentApp\Communication\Controller;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\PaymentApp\Business\RequestExecutor\ExpressCheckoutPaymentRequestExecutorInterface;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeInterface;
use SprykerTest\Zed\PaymentApp\PaymentAppCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentApp
 * @group Communication
 * @group Controller
 * @group GatewayControllerInitializePreOrderPaymentTest
 * Add your own group annotations below this line
 */
class GatewayControllerInitializePreOrderPaymentTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PaymentApp\PaymentAppCommunicationTester
     */
    protected PaymentAppCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGivenPreOrderPaymentRequestTransferWithoutQuoteWhenTheInitializePreOrderPaymentActionIsCalledThenAnExceptionIsThrown(): void
    {
        // Arrange
        $preOrderPaymentRequestTransfer = $this->tester->havePreOrderPaymentRequestTransferWithoutQuote();

        // Expect
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\PreOrderPaymentRequestTransfer` is null.');

        // Act
        $this->tester->getGatewayController()->initializePreOrderPaymentAction($preOrderPaymentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGivenValidPreOrderPaymentRequestTransferWhenTheInitializePreOrderPaymentActionIsCalledAndTheProcessExpressCheckoutPaymentRequestReturnsErrorsThenAFailedResponseWithAnErrorMessageIsReturned(): void
    {
        // Arrange
        $preOrderPaymentRequestTransfer = $this->tester->havePreOrderPaymentRequestTransferWithQuote();

        $this->tester->mockFactoryMethod('getPaymentFacade', function () {
            return Stub::makeEmpty(PaymentAppToPaymentFacadeInterface::class);
        });

        $expressCheckoutPaymentRequestExecutorMock = Stub::makeEmpty(ExpressCheckoutPaymentRequestExecutorInterface::class, [
            'processExpressCheckoutPaymentRequest' => function () {
                $expressCheckoutPaymentResponseTransfer = new ExpressCheckoutPaymentResponseTransfer();
                $expressCheckoutPaymentResponseTransfer->addError((new ErrorTransfer())->setMessage('first message'));
                $expressCheckoutPaymentResponseTransfer->addError((new ErrorTransfer())->setMessage('second message'));

                return $expressCheckoutPaymentResponseTransfer;
            },
        ]);

        $this->tester->mockFactoryMethod('createExpressCheckoutPaymentRequestExecutor', $expressCheckoutPaymentRequestExecutorMock);

        // Act
        $preOrderPaymentResponseTransfer = $this->tester->getGatewayController()->initializePreOrderPaymentAction($preOrderPaymentRequestTransfer);

        // Assert
        $this->assertFalse($preOrderPaymentResponseTransfer->getIsSuccessful());
        $this->assertSame('first message, second message', $preOrderPaymentResponseTransfer->getError());
    }
}
