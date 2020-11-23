<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CheckoutRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Plugin\CheckoutRestApi\SinglePaymentCheckoutRequestValidatorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CheckoutRestApi
 * @group Plugin
 * @group SinglePaymentCheckoutRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class SinglePaymentCheckoutRequestValidatorPluginTest extends Unit
{
    /**
     * @var \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestValidatorPluginInterface
     */
    protected $singlePaymentCheckoutRequestValidatorPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->singlePaymentCheckoutRequestValidatorPlugin = new SinglePaymentCheckoutRequestValidatorPlugin();
    }

    /**
     * @return void
     */
    public function testValidateAttributesWillNotReturnErrorIfOnlyOnePaymentMethodWasProvided(): void
    {
        // Arrange
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())->withPayment()->build();

        // Act
        $restErrorCollectionTransfer = $this->singlePaymentCheckoutRequestValidatorPlugin->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertCount(0, $restErrorCollectionTransfer->getRestErrors());
    }

    /**
     * @return void
     */
    public function testValidateAttributesWillNotReturnErrorWithExpectedCodeIfMoreThanOnePaymentMethodWasProvided(): void
    {
        // Arrange
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withPayment()
            ->withAnotherPayment()
            ->build();

        // Act
        $restErrorCollectionTransfer = $this->singlePaymentCheckoutRequestValidatorPlugin->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertCount(1, $restErrorCollectionTransfer->getRestErrors());
        /** @var \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer */
        $restErrorMessageTransfer = $restErrorCollectionTransfer->getRestErrors()->offsetGet(0);
        $this->assertEquals(CheckoutRestApiConfig::RESPONSE_CODE_MULTIPLE_PAYMENTS_NOT_ALLOWED, $restErrorMessageTransfer->getCode());
    }
}
