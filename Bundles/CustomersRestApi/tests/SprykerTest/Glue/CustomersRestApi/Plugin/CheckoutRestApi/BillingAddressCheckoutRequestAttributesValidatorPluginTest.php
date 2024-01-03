<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CustomersRestApi\Plugin\CheckoutRestApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\Plugin\CheckoutRestApi\BillingAddressCheckoutRequestAttributesValidatorPlugin;
use SprykerTest\Glue\CustomersRestApi\CustomersRestApiPluginTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CustomersRestApi
 * @group Plugin
 * @group CheckoutRestApi
 * @group BillingAddressCheckoutRequestAttributesValidatorPluginTest
 * Add your own group annotations below this line
 */
class BillingAddressCheckoutRequestAttributesValidatorPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\CustomersRestApi\Processor\Checker\CheckoutBillingAddressChecker::RESPONSE_CODE_REQUEST_INVALID
     *
     * @var string
     */
    protected const RESPONSE_CODE_REQUEST_INVALID = '901';

    /**
     * @uses \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY
     *
     * @var int
     */
    protected const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var int
     */
    protected const FAKE_ID_ADDRESS = 123456;

    /**
     * @var \SprykerTest\Glue\CustomersRestApi\CustomersRestApiPluginTester
     */
    protected CustomersRestApiPluginTester $tester;

    /**
     * @return void
     */
    public function testShouldIgnoreValidationWhenBillingAddressIsEmpty(): void
    {
        // Arrange
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->setBillingAddress(null);

        // Act
        $restErrorCollectionTransfer = (new BillingAddressCheckoutRequestAttributesValidatorPlugin())->validateAttributes(
            $restCheckoutRequestAttributesTransfer,
        );

        // Assert
        $this->assertEmpty($restErrorCollectionTransfer->getRestErrors());
    }

    /**
     * @return void
     */
    public function testShouldIgnoreValidationWhenBillingAddressHasIdField(): void
    {
        // Arrange
        $billingAddress = (new RestAddressTransfer())->setId(static::FAKE_ID_ADDRESS);
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->setBillingAddress($billingAddress);

        // Act
        $restErrorCollectionTransfer = (new BillingAddressCheckoutRequestAttributesValidatorPlugin())->validateAttributes(
            $restCheckoutRequestAttributesTransfer,
        );

        // Assert
        $this->assertEmpty($restErrorCollectionTransfer->getRestErrors());
    }

    /**
     * @dataProvider getMandatoryBillingAddressFieldDataProvider
     *
     * @param \Generated\Shared\Transfer\RestAddressTransfer $billingAddress
     * @param array<\Generated\Shared\Transfer\RestErrorMessageTransfer> $restErrorMessageTransfers
     *
     * @return void
     */
    public function testShouldValidateMandatoryBillingAddressFields(
        RestAddressTransfer $billingAddress,
        array $restErrorMessageTransfers
    ): void {
        // Arrange
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->setBillingAddress($billingAddress);

        // Act
        $restErrorCollectionTransfer = (new BillingAddressCheckoutRequestAttributesValidatorPlugin())->validateAttributes(
            $restCheckoutRequestAttributesTransfer,
        );

        // Assert
        foreach ($restErrorMessageTransfers as $offset => $restErrorMessageTransfer) {
            $restError = $restErrorCollectionTransfer->getRestErrors()->offsetGet($offset);

            $this->assertSame($restErrorMessageTransfer->getCode(), $restError->getCode());
            $this->assertSame($restErrorMessageTransfer->getStatus(), $restError->getStatus());
            $this->assertSame($restErrorMessageTransfer->getDetail(), $restError->getDetail());
        }
    }

    /**
     * @return array
     */
    public function getMandatoryBillingAddressFieldDataProvider(): array
    {
        return [
            [
                $this->createBillingAddress(),
                [],
            ],
            [
                $this->createBillingAddress()->setSalutation(null),
                [$this->createRestErrorMessage('salutation')],
            ],
            [
                $this->createBillingAddress()->setFirstName(null),
                [$this->createRestErrorMessage('firstName')],
            ],
            [
                $this->createBillingAddress()->setLastName(null),
                [$this->createRestErrorMessage('lastName')],
            ],
            [
                $this->createBillingAddress()->setAddress1(null),
                [$this->createRestErrorMessage('address1')],
            ],
            [
                $this->createBillingAddress()->setAddress2(null),
                [$this->createRestErrorMessage('address2')],
            ],
            [
                $this->createBillingAddress()->setZipCode(null),
                [$this->createRestErrorMessage('zipCode')],
            ],
            [
                $this->createBillingAddress()->setCity(null),
                [$this->createRestErrorMessage('city')],
            ],
            [
                $this->createBillingAddress()->setIso2Code(null),
                [$this->createRestErrorMessage('iso2Code')],
            ],
            [
                $this->createBillingAddress()->setAddress1(null)->setIso2Code(null),
                [
                    $this->createRestErrorMessage('address1'),
                    $this->createRestErrorMessage('iso2Code'),
                ],
            ],
            [
                new RestAddressTransfer(),
                [
                    $this->createRestErrorMessage('salutation'),
                    $this->createRestErrorMessage('firstName'),
                    $this->createRestErrorMessage('lastName'),
                    $this->createRestErrorMessage('address1'),
                    $this->createRestErrorMessage('address2'),
                    $this->createRestErrorMessage('zipCode'),
                    $this->createRestErrorMessage('city'),
                    $this->createRestErrorMessage('iso2Code'),
                ],
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\RestAddressTransfer
     */
    protected function createBillingAddress(): RestAddressTransfer
    {
        return (new RestAddressTransfer())
            ->setSalutation('Mr')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Address 1')
            ->setAddress2('Address 2')
            ->setZipCode('12345')
            ->setCity('Berlin')
            ->setIso2Code('DE');
    }

    /**
     * @param string $restAddressField
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createRestErrorMessage(string $restAddressField): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setStatus(static::HTTP_UNPROCESSABLE_ENTITY)
            ->setCode(static::RESPONSE_CODE_REQUEST_INVALID)
            ->setDetail(sprintf('billingAddress.%s => This field is missing.', $restAddressField));
    }
}
