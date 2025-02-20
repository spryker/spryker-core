<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Generator;

use Codeception\Test\Unit;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Spryker\Zed\Transfer\TransferConfig;
use SprykerTest\Zed\Transfer\TransferBusinessTester;
use Symfony\Component\Console\Logger\ConsoleLogger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group Generator
 * @group TransferDefinitionMergerTest
 * Add your own group annotations below this line
 */
class TransferDefinitionMergerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Transfer\TransferBusinessTester
     */
    protected TransferBusinessTester $tester;

    /**
     * @return void
     */
    public function testMergeShouldReturnMergedTransferDefinition(): void
    {
        $helper = new TransferDefinitionMergerHelper();
        $transferDefinitions = [
            $helper->getTransferDefinition1(),
            $helper->getTransferDefinition2(),
        ];

        $expected = [];
        $expected['Transfer'] = $helper->getExpectedTransfer();
        $transferConfig = new TransferConfig();

        $merger = new TransferDefinitionMerger($transferConfig, $this->getMessengerMock());
        $this->assertEquals($expected, $merger->merge($transferDefinitions));
    }

    /**
     * @return void
     */
    public function testMergeShouldMergeWithSameNameDefineDifferentAttributesWhenOverrideIsActiveForDataBuilderRuleAttribute(): void
    {
        // Arrange
        $helper = new TransferDefinitionMergerHelper();
        $property1 = $helper->getTransferDefinition1();

        $property1['property'] = [
            [
                'name' => 'propertyA',
                'dataBuilderRule' => 'shuffle(array("new"))',
            ],
        ];

        $transferDefinitions = [
            $property1,
            $helper->getTransferDefinition2(),
        ];

        $expected = [];
        $expected['Transfer'] = $helper->getExpectedTransfer();
        $transferConfigMock = $this->getMockBuilder(TransferConfig::class)->onlyMethods(['isProjectTransferOverrideActive'])->getMock();
        $transferConfigMock->method('isProjectTransferOverrideActive')->willReturn(true);

        $merger = new TransferDefinitionMerger($transferConfigMock, $this->getMessengerMock());

        // Act
        $transferDefinition = $merger->merge($transferDefinitions);

        // Assert
        $this->assertSame('shuffle(array("new"))', $transferDefinition['Transfer']['property']['propertyA']['dataBuilderRule']);
    }

    /**
     * @return void
     */
    public function testMergeShouldThrowExceptionIfTwoPropertiesWithSameNameDefineDifferentAttributes(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Value mismatch for "Transfer.propertyA" transfer property. Value1: "int"; Value2: "string". To fix this, search for \'property name="propertyA"\' in the code base and fix the wrong one.');
        $helper = new TransferDefinitionMergerHelper();
        $property1 = $helper->getTransferDefinition1();

        $property1['property'] = [
            [
                'name' => 'propertyA',
                'type' => 'int',
            ],
        ];

        $transferDefinitions = [
            $property1,
            $helper->getTransferDefinition2(),
        ];

        $expected = [];
        $expected['Transfer'] = $helper->getExpectedTransfer();
        $transferConfig = new TransferConfig();

        $merger = new TransferDefinitionMerger($transferConfig, $this->getMessengerMock());

        $merger->merge($transferDefinitions);
    }

    /**
     * @return \Symfony\Component\Console\Logger\ConsoleLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMessengerMock(): ConsoleLogger
    {
        return $this->getMockBuilder(ConsoleLogger::class)->disableOriginalConstructor()->getMock();
    }
}
