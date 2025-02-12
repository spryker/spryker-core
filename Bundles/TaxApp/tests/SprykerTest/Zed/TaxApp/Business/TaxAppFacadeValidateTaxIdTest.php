<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppValidationResponseTransfer;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;
use SprykerTest\Zed\TaxApp\TaxAppBusinessTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TaxApp
 * @group Business
 * @group Facade
 * @group TaxAppFacadeValidateTaxIdTest
 * Add your own group annotations below this line
 */
class TaxAppFacadeValidateTaxIdTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\TaxApp\TaxAppBusinessTester
     */
    protected TaxAppBusinessTester $tester;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureTaxIdValidationHistoryTableIsEmpty();
        $this->storeTransfer = $this->tester->haveStore([StoreTransfer::COUNTRIES => ['US']], false);
        $this->tester->mockOauthClient();

        $storeFacadeMock = Stub::makeEmpty(TaxAppToStoreFacadeInterface::class, [
            'getCurrentStore' => $this->storeTransfer,
        ]);
        $this->tester->mockFactoryMethod('getStoreFacade', $storeFacadeMock);
    }

    /**
     * @return void
     */
    public function testGivenAValidTaxIdWhenTheApiReturnsASuccessfulResponseThenATaxIdValidationHistoryEntryIsCreated(): void
    {
        // Arrange
        $taxAppValidationRequestTransfer = $this->tester->createTaxAppValidationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $this->storeTransfer->getIdStore(), 'is_active' => true]);
        $taxAppValidationResponseTransfer = (new TaxAppValidationResponseTransfer())
            ->setAdditionalInfo('test')
            ->setIsValid(true);

        $this->tester->mockKernelAppFacade(
            (new AcpHttpResponseTransfer())
                ->setHttpStatusCode(Response::HTTP_OK)
                ->setContent(json_encode($taxAppValidationResponseTransfer->toArray())),
            $this->callback(function (AcpHttpRequestTransfer $incomingAcpHttpRequestTransfer) use ($taxAppValidationRequestTransfer, $taxAppConfigTransfer) {
                $this->assertSame($taxAppConfigTransfer->getApiUrls()->getTaxIdValidationUrl(), $incomingAcpHttpRequestTransfer->getUri());
                $this->assertSame('POST', $incomingAcpHttpRequestTransfer->getMethod());
                $this->assertEqualsCanonicalizing(
                    $taxAppValidationRequestTransfer->toArray(true, true),
                    json_decode($incomingAcpHttpRequestTransfer->getBody(), true),
                );
                $this->assertArrayHasKey('Authorization', $incomingAcpHttpRequestTransfer->getHeaders());

                return true;
            }),
        );

        // Act
        $taxAppValidationResponseTransfer = $this->tester->getFacade()->validateTaxId($taxAppValidationRequestTransfer);

        // Assert
        $this->assertTrue($taxAppValidationResponseTransfer->getIsValid());
        $this->tester->assertTaxIdValidationHistoryEntryDoesNotExist($taxAppValidationRequestTransfer->getTaxId(), $taxAppValidationRequestTransfer->getCountryCode(), $taxAppValidationResponseTransfer->getAdditionalInfo());
    }

    /**
     * @return void
     */
    public function testGivenAMalformedRequestWhenTheTaxIdValidationApiIsCalledThenTheResponseContainsAServiceUnavailableMessage(): void
    {
        // Arrange
        $taxAppValidationRequestTransfer = $this->tester->createTaxAppValidationRequestTransfer();
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $this->storeTransfer->getIdStore(), 'is_active' => true]);
        $this->tester->mockKernelAppFacade(
            (new AcpHttpResponseTransfer())
                ->setHttpStatusCode(Response::HTTP_BAD_REQUEST),
        );

        // Act
        $taxAppValidationResponseTransfer = $this->tester->getFacade()->validateTaxId($taxAppValidationRequestTransfer);

        // Assert
        $this->assertFalse($taxAppValidationResponseTransfer->getIsValid());
        $this->assertSame('Tax Validator API is unavailable.', $taxAppValidationResponseTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGivenAMalformedRequestWhenTheTaxIdValidationApiIsCalledThenAFailedResponseIsReturned(): void
    {
        // Arrange
        $taxAppValidationRequestTransfer = $this->tester->createTaxAppValidationRequestTransfer();
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $this->storeTransfer->getIdStore(), 'is_active' => true]);
        $this->tester->mockKernelAppFacade(
            (new AcpHttpResponseTransfer())
                ->setHttpStatusCode(Response::HTTP_OK)
                ->setContent(json_encode(
                    (new TaxAppValidationResponseTransfer())
                        ->setIsValid(false)
                        ->setMessage('message')
                        ->toArray(),
                )),
        );

        // Act
        $taxAppValidationResponseTransfer = $this->tester->getFacade()->validateTaxId($taxAppValidationRequestTransfer);

        // Assert
        $this->assertFalse($taxAppValidationResponseTransfer->getIsValid());
        $this->assertSame('message', $taxAppValidationResponseTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateTaxIdWhenServiceIsDisabledThenTheErrorMessageIsReturnedInTheResponse(): void
    {
        // Arrange
        $taxAppValidationRequestTransfer = $this->tester->createTaxAppValidationRequestTransfer();
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $this->storeTransfer->getIdStore(), 'is_active' => false]);

        // Act
        $taxAppValidationResponseTransfer = $this->tester->getFacade()->validateTaxId($taxAppValidationRequestTransfer);

        // Assert
        $this->assertFalse($taxAppValidationResponseTransfer->getIsValid());
        $this->assertSame('Tax service is disabled.', $taxAppValidationResponseTransfer->getMessage());
    }
}
