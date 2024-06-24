<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommissionGui\Communication\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\MerchantCommissionGui\Communication\Mapper\MerchantCommissionCsvMapper;
use Spryker\Zed\MerchantCommissionGui\Communication\Mapper\MerchantCommissionCsvMapperInterface;
use Spryker\Zed\MerchantCommissionGui\Communication\Transformer\MerchantCommissionAmountTransformerInterface;
use SprykerTest\Zed\MerchantCommissionGui\MerchantCommissionGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommissionGui
 * @group Communication
 * @group Mapper
 * @group MerchantCommissionCsvMapperTest
 * Add your own group annotations below this line
 */
class MerchantCommissionCsvMapperTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_MERCHANT_COMMISSION_KEY
     *
     * @var string
     */
    protected const KEY_MERCHANT_COMMISSION_KEY = 'key';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_NAME
     *
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_DESCRIPTION
     *
     * @var string
     */
    protected const KEY_DESCRIPTION = 'description';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_VALID_FROM
     *
     * @var string
     */
    protected const KEY_VALID_FROM = 'valid_from';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_VALID_TO
     *
     * @var string
     */
    protected const KEY_VALID_TO = 'valid_to';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_IS_ACTIVE
     *
     * @var string
     */
    protected const KEY_IS_ACTIVE = 'is_active';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_AMOUNT
     *
     * @var string
     */
    protected const KEY_AMOUNT = 'amount';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_CALCULATOR_TYPE_PLUGIN
     *
     * @var string
     */
    protected const KEY_CALCULATOR_TYPE_PLUGIN = 'calculator_type_plugin';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_GROUP
     *
     * @var string
     */
    protected const KEY_GROUP = 'group';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_PRIORITY
     *
     * @var string
     */
    protected const KEY_PRIORITY = 'priority';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_ITEM_CONDITION
     *
     * @var string
     */
    protected const KEY_ITEM_CONDITION = 'item_condition';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_ORDER_CONDITION
     *
     * @var string
     */
    protected const KEY_ORDER_CONDITION = 'order_condition';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_STORES
     *
     * @var string
     */
    protected const KEY_STORES = 'stores';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_MERCHANTS_ALLOW_LIST
     *
     * @var string
     */
    protected const KEY_MERCHANTS_ALLOW_LIST = 'merchants_allow_list';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig::KEY_FIXED_AMOUNT_CONFIGURATION
     *
     * @var string
     */
    protected const KEY_FIXED_AMOUNT_CONFIGURATION = 'fixed_amount_configuration';

    /**
     * @var \SprykerTest\Zed\MerchantCommissionGui\MerchantCommissionGuiCommunicationTester
     */
    protected MerchantCommissionGuiCommunicationTester $tester;

    /**
     * @return void
     */
    public function testMapMerchantCommissionRowDataToMerchantCommissionTransferReturnsCorrectlyMappedMerchantCommissionTransfer(): void
    {
        // Arrange
        $merchantCommissionRowData = $this->getMerchantCommissionRowData();

        // Act
        $merchantCommissionTransfer = $this->createMerchantCommissionCsvMapper()->mapMerchantCommissionRowDataToMerchantCommissionTransfer(
            $merchantCommissionRowData,
            new MerchantCommissionTransfer(),
        );

        // Assert
        $this->assertSame($merchantCommissionRowData[static::KEY_MERCHANT_COMMISSION_KEY], $merchantCommissionTransfer->getKey());
        $this->assertSame($merchantCommissionRowData[static::KEY_NAME], $merchantCommissionTransfer->getName());
        $this->assertSame($merchantCommissionRowData[static::KEY_DESCRIPTION], $merchantCommissionTransfer->getDescription());
        $this->assertSame($merchantCommissionRowData[static::KEY_VALID_FROM], $merchantCommissionTransfer->getValidFrom());
        $this->assertSame($merchantCommissionRowData[static::KEY_VALID_TO], $merchantCommissionTransfer->getValidTo());
        $this->assertSame((bool)$merchantCommissionRowData[static::KEY_IS_ACTIVE], $merchantCommissionTransfer->getIsActive());
        $this->assertSame((int)$merchantCommissionRowData[static::KEY_AMOUNT], $merchantCommissionTransfer->getAmount());
        $this->assertSame($merchantCommissionRowData[static::KEY_CALCULATOR_TYPE_PLUGIN], $merchantCommissionTransfer->getCalculatorTypePlugin());
        $this->assertSame((int)$merchantCommissionRowData[static::KEY_PRIORITY], $merchantCommissionTransfer->getPriority());
        $this->assertSame($merchantCommissionRowData[static::KEY_ITEM_CONDITION], $merchantCommissionTransfer->getItemCondition());
        $this->assertSame($merchantCommissionRowData[static::KEY_ORDER_CONDITION], $merchantCommissionTransfer->getOrderCondition());

        $this->assertNotNull($merchantCommissionTransfer->getMerchantCommissionGroup());
        $this->assertSame($merchantCommissionRowData[static::KEY_GROUP], $merchantCommissionTransfer->getMerchantCommissionGroup()->getKey());

        $this->assertNotNull($merchantCommissionTransfer->getStoreRelation());
        $this->assertCount(2, $merchantCommissionTransfer->getStoreRelation()->getStores());
        $expectedStoreNames = explode(',', $merchantCommissionRowData[static::KEY_STORES]);
        $this->assertSame($expectedStoreNames[0], $merchantCommissionTransfer->getStoreRelation()->getStores()->offsetGet(0)->getName());
        $this->assertSame($expectedStoreNames[1], $merchantCommissionTransfer->getStoreRelation()->getStores()->offsetGet(1)->getName());

        $this->assertCount(2, $merchantCommissionTransfer->getMerchants());
        $expectedMerchantReferences = explode(',', $merchantCommissionRowData[static::KEY_MERCHANTS_ALLOW_LIST]);
        $this->assertSame($expectedMerchantReferences[0], $merchantCommissionTransfer->getMerchants()->offsetGet(0)->getMerchantReference());
        $this->assertSame($expectedMerchantReferences[1], $merchantCommissionTransfer->getMerchants()->offsetGet(1)->getMerchantReference());

        $this->assertCount(2, $merchantCommissionTransfer->getMerchantCommissionAmounts());
        $this->assertSame(0, $merchantCommissionTransfer->getMerchantCommissionAmounts()->offsetGet(0)->getNetAmount());
        $this->assertSame(5, $merchantCommissionTransfer->getMerchantCommissionAmounts()->offsetGet(0)->getGrossAmount());
        $this->assertSame('EUR', $merchantCommissionTransfer->getMerchantCommissionAmounts()->offsetGet(0)->getCurrency()->getCode());
        $this->assertSame(0, $merchantCommissionTransfer->getMerchantCommissionAmounts()->offsetGet(1)->getNetAmount());
        $this->assertSame(10, $merchantCommissionTransfer->getMerchantCommissionAmounts()->offsetGet(1)->getGrossAmount());
        $this->assertSame('CHF', $merchantCommissionTransfer->getMerchantCommissionAmounts()->offsetGet(1)->getCurrency()->getCode());
    }

    /**
     * @return array<string, string>
     */
    protected function getMerchantCommissionRowData(): array
    {
        return [
            static::KEY_MERCHANT_COMMISSION_KEY => 'test-mc-1',
            static::KEY_NAME => 'Test Merchant Commission 1',
            static::KEY_DESCRIPTION => 'Test Merchant Commission 1 Description',
            static::KEY_VALID_FROM => '2024-01-01',
            static::KEY_VALID_TO => '2024-12-31',
            static::KEY_IS_ACTIVE => '1',
            static::KEY_AMOUNT => '10',
            static::KEY_CALCULATOR_TYPE_PLUGIN => 'test-calculator-plugin',
            static::KEY_GROUP => 'test-group',
            static::KEY_PRIORITY => '1',
            static::KEY_ITEM_CONDITION => 'test-item-condition',
            static::KEY_ORDER_CONDITION => 'test-order-condition',
            static::KEY_STORES => 'DE,AT',
            static::KEY_MERCHANTS_ALLOW_LIST => 'test-merchant-1,test-merchant-2',
            static::KEY_FIXED_AMOUNT_CONFIGURATION => 'EUR|0|5,CHF|0|10',
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Mapper\MerchantCommissionCsvMapperInterface
     */
    protected function createMerchantCommissionCsvMapper(): MerchantCommissionCsvMapperInterface
    {
        return new MerchantCommissionCsvMapper($this->getMerchantCommissionAmountTransformerMock());
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Transformer\MerchantCommissionAmountTransformerInterface
     */
    protected function getMerchantCommissionAmountTransformerMock(): MerchantCommissionAmountTransformerInterface
    {
        $merchantCommissionAmountTransformerMock = $this->getMockBuilder(MerchantCommissionAmountTransformerInterface::class)
            ->getMock();

        $merchantCommissionAmountTransformerMock
            ->method('transformMerchantCommissionAmount')
            ->willReturnCallback(function (string $merchantCommissionCalculatorPluginType, float $amount) {
                return (int)$amount;
            });

        return $merchantCommissionAmountTransformerMock;
    }
}
