<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Persistence\Propel\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Quote\Business\Quote\QuoteFieldsConfigurator;
use Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceBridge;
use Spryker\Zed\Quote\Persistence\Propel\Mapper\QuoteMapper;
use Spryker\Zed\Quote\QuoteConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Quote
 * @group Persistence
 * @group Propel
 * @group Mapper
 * @group QuoteMapperTest
 * Add your own group annotations below this line
 */
class QuoteMapperTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Quote\QuotePersistenceTester
     */
    protected $tester;

    /**
     * @dataProvider mapTransferToEntityDataProvider
     *
     * @param array $quoteAllowedFields
     * @param array $quoteItemAllowedFields
     *
     * @return void
     */
    public function testMapTransferToEntity(array $quoteAllowedFields, array $quoteItemAllowedFields): void
    {
        // Arrange
        $utilEncodingService = $this->getUtilEncodingService();
        $quoteFieldsConfigurator = new QuoteFieldsConfigurator($this->createQuoteConfigMock($quoteAllowedFields, $quoteItemAllowedFields));
        $quoteMapper = new QuoteMapper(new QuoteToUtilEncodingServiceBridge($utilEncodingService));
        $quoteTransfer = $this->tester->createQuoteTransfer();

        $quoteFieldsAllowedForSaving = $quoteFieldsConfigurator->getQuoteFieldsAllowedForSaving($quoteTransfer);

        // Act
        $updatedQuoteEntity = $quoteMapper->mapTransferToEntity(
            $quoteTransfer,
            $this->tester->createQuotePropelEntity(),
            $quoteFieldsAllowedForSaving
        );
        $decodedQuoteData = $utilEncodingService->decodeJson($updatedQuoteEntity->getQuoteData(), true);

        // Assert
        $this->tester->assertContainOnlyAllowedFields($quoteFieldsAllowedForSaving, $decodedQuoteData);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->tester->getLocator()->utilEncoding()->service();
    }

    /**
     * @param array $quoteAllowedFields
     * @param array $quoteItemAllowedFields
     *
     * @return \Spryker\Zed\Quote\QuoteConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteConfigMock(array $quoteAllowedFields, array $quoteItemAllowedFields): QuoteConfig
    {
        $quoteConfigMock = $this->getMockBuilder(QuoteConfig::class)
            ->onlyMethods(['getQuoteFieldsAllowedForSaving', 'getQuoteItemFieldsAllowedForSaving'])
            ->getMock();

        $quoteConfigMock->method('getQuoteFieldsAllowedForSaving')->willReturn($quoteAllowedFields);
        $quoteConfigMock->method('getQuoteItemFieldsAllowedForSaving')->willReturn($quoteItemAllowedFields);

        return $quoteConfigMock;
    }

    /**
     * @return array
     */
    public function mapTransferToEntityDataProvider(): array
    {
        return [
            [
                'quoteAllowedFields' => [
                    QuoteTransfer::ITEMS,
                    QuoteTransfer::TOTALS,
                ],
                'quoteItemAllowedFields' => [
                    ItemTransfer::SKU,
                    ItemTransfer::IMAGES,
                ],
            ],
            [
                'quoteAllowedFields' => [
                    QuoteTransfer::ITEMS,
                    QuoteTransfer::TOTALS,
                    QuoteTransfer::CURRENCY,
                    QuoteTransfer::PRICE_MODE,
                ],
                'quoteItemAllowedFields' => [],
            ],
        ];
    }
}
