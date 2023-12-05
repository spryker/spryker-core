<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\TaxApp\Api;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Client\TaxApp\TaxAppClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group TaxApp
 * @group Api
 * @group TaxAppRequestSenderTest
 * Add your own group annotations below this line
 */
class TaxAppRequestSenderTest extends Unit
{
    /**
     * @var array<string, string>
     */
    protected const REQUEST_HEADERS = [
        'X-Store-Reference' => 'store-reference',
    ];

    /**
     * @var \SprykerTest\Client\TaxApp\TaxAppClientTester
     */
    protected TaxAppClientTester $tester;

    /**
     * @return void
     */
    public function testTaxQuotationRequestHasSuccessfulResponseTransfer(): void
    {
        // Arrange
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();
        $this->tester->mockHttpClient($this->tester->haveValidResponse());
        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->mockStoreClient($storeTransfer);

        // Act
        $responseTransfer = $client->requestTaxQuotation($taxCalculationRequestTransfer, $taxAppConfigTransfer, $storeTransfer);

        // Assert
        $this->tester->assertTaxCalculationResponseIsNotEmpty($responseTransfer);
        $this->assertTrue($responseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testTaxQuotationRequestHasUnsuccessfulResponseTransferWhenHttpResponseIsEmpty(): void
    {
        // Arrange
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();
        $this->tester->mockHttpClient($this->tester->haveEmptyResponse());
        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->mockStoreClient($storeTransfer);

        // Act
        $responseTransfer = $client->requestTaxQuotation($taxCalculationRequestTransfer, $taxAppConfigTransfer, $storeTransfer);

        // Assert
        $this->tester->assertTaxCalculationResponseIsNotEmpty($responseTransfer);
        $this->assertFalse($responseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testTaxQuotationRequestHasUnsuccessfulResponseTransferWhenHttpResponseContainsError(): void
    {
        // Arrange
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();
        $this->tester->mockHttpClient($this->tester->haveErrorResponse());
        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->mockStoreClient($storeTransfer);

        // Act
        $responseTransfer = $client->requestTaxQuotation($taxCalculationRequestTransfer, $taxAppConfigTransfer, $storeTransfer);

        // Assert
        $this->tester->assertTaxCalculationResponseIsNotEmpty($responseTransfer);
        $this->assertFalse($responseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testTaxQuotationRequestSucceedsWhenTenantIdentifierIsPresent(): void
    {
        // Arrange
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer(['isActive' => true, 'tenant_identifier' => 'tenant-1']);
        $this->tester->mockHttpClient($this->tester->haveValidResponse());
        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = new StoreTransfer();

        // Act
        $responseTransfer = $client->requestTaxQuotation($taxCalculationRequestTransfer, $taxAppConfigTransfer, $storeTransfer);

        // Assert
        $this->assertTrue($responseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testTaxQuotationRequestFailsWhenNeitherTenantIdentifierOrStoreArePresent(): void
    {
        // Arrange
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer(['is_active' => true, 'tenant_identifier' => null]);
        $this->tester->mockHttpClient($this->tester->haveEmptyResponse());
        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = new StoreTransfer();

        // Act
        $responseTransfer = $client->requestTaxQuotation($taxCalculationRequestTransfer, $taxAppConfigTransfer, $storeTransfer);

        // Assert
        $this->assertFalse($responseTransfer->getIsSuccessful());
        $this->assertStringContainsString('Tenant identifier or store reference or store name must be set.', $responseTransfer->getApiErrorMessages()[0]->getDetail());
    }

    /**
     * @return void
     */
    public function testTaxQuotationRequestSucceedsWhenTenantIdentifierIsNotPresentButStoreIsPresent(): void
    {
        // Arrange
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer(['is_active' => true, 'tenant_identifier' => null]);
        $this->tester->mockHttpClient($this->tester->haveValidResponse());
        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->mockStoreClient($storeTransfer);

        // Act
        $responseTransfer = $client->requestTaxQuotation($taxCalculationRequestTransfer, $taxAppConfigTransfer, $storeTransfer);

        // Assert
        $this->assertTrue($responseTransfer->getIsSuccessful());
    }
}
