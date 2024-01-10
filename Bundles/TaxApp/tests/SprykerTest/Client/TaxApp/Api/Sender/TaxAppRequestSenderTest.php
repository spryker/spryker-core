<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\TaxApp\Api;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\TaxApp\Dependency\External\TaxAppToHttpClientAdapterInterface;
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
     * @see \Spryker\Client\TaxApp\Api\Builder\TaxAppHeaderBuilder::HEADER_TENANT_IDENTIFIER
     *
     * @var string
     */
    protected const HEADER_TENANT_IDENTIFIER = 'X-Tenant-Identifier';

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
        $this->tester->mockConfig();
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
    public function testTaxRefundRequestHasSuccessfulResponseTransfer(): void
    {
        // Arrange
        $this->tester->mockConfig();
        $taxRefundRequestTransfer = $this->tester->haveTaxRefundRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();
        $this->tester->mockHttpClient($this->tester->haveValidResponse());
        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->mockStoreClient($storeTransfer);

        // Act
        $responseTransfer = $client->requestTaxRefund($taxRefundRequestTransfer, $taxAppConfigTransfer, $storeTransfer);

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
        $this->tester->mockConfig();
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
    public function testTaxQuotationRequestHasUnsuccessfulResponseTransferWhenQuotationUrlIsMissing(): void
    {
        // Arrange
        $this->tester->mockConfig();
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();
        $taxAppConfigTransfer->getApiUrlsOrFail()->setQuotationUrl(null);
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
    public function testTaxRefundRequestHasUnsuccessfulResponseTransferWhenQuotationUrlIsMissing(): void
    {
        // Arrange
        $this->tester->mockConfig();
        $taxCalculationRequestTransfer = $this->tester->haveTaxRefundRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();
        $taxAppConfigTransfer->getApiUrlsOrFail()->setRefundsUrl(null);
        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->mockStoreClient($storeTransfer);

        // Act
        $responseTransfer = $client->requestTaxRefund($taxCalculationRequestTransfer, $taxAppConfigTransfer, $storeTransfer);

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
        $this->tester->mockConfig();
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
        $this->tester->mockConfig([
            'getTenantIdentifier' => 'test-tenant-identifier',
        ]);
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer(['isActive' => true]);
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
        $this->tester->mockConfig();
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
        $this->tester->mockConfig();
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

    /**
     * @return void
     */
    public function testTaxQuotationRequestHasSuccessfulResponseTransferWithCorrectHeadersWhenStoreReferenceIsSet(): void
    {
        // Arrange
        $storeReference = 'dev-DE';
        $this->tester->mockConfig();
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();
        $response = $this->tester->haveValidResponse();

        $httpClientMock = $this->createMock(TaxAppToHttpClientAdapterInterface::class);
        $httpClientMock->method('request')->willReturnCallback(function (string $method, string $uri, array $options = []) use ($storeReference, $response) {
            $this->assertArrayHasKey(static::HEADER_TENANT_IDENTIFIER, $options['headers']);
            $this->assertSame($options['headers'][static::HEADER_TENANT_IDENTIFIER], $storeReference);

            return $response;
        });
        $this->tester->mockFactoryMethod('getHttpClient', $httpClientMock);

        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => $storeReference], false);
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
    public function testTaxQuotationRequestHasSuccessfulResponseTransferWithCorrectHeadersWhenTenantIdentifierIsSet(): void
    {
        // Arrange
        $tenantIdentifier = 'test-tenant-identifier';
        $this->tester->mockConfig([
            'getTenantIdentifier' => $tenantIdentifier,
        ]);
        $taxCalculationRequestTransfer = $this->tester->haveTaxCalculationRequestTransfer();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();
        $response = $this->tester->haveValidResponse();

        $httpClientMock = $this->createMock(TaxAppToHttpClientAdapterInterface::class);
        $httpClientMock->method('request')
            ->willReturnCallback(function (string $method, string $uri, array $options = []) use ($tenantIdentifier, $response) {
                $this->assertArrayHasKey(static::HEADER_TENANT_IDENTIFIER, $options['headers']);
                $this->assertSame($options['headers'][static::HEADER_TENANT_IDENTIFIER], $tenantIdentifier);

                return $response;
            });
        $this->tester->mockFactoryMethod('getHttpClient', $httpClientMock);

        $client = $this->tester->getFactory()->createTaxAppRequestSender();
        $storeTransfer = new StoreTransfer();
        $this->tester->mockStoreClient($storeTransfer);

        // Act
        $responseTransfer = $client->requestTaxQuotation($taxCalculationRequestTransfer, $taxAppConfigTransfer, $storeTransfer);

        // Assert
        $this->tester->assertTaxCalculationResponseIsNotEmpty($responseTransfer);
        $this->assertTrue($responseTransfer->getIsSuccessful());
    }
}
