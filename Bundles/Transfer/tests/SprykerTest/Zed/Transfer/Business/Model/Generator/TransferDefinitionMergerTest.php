<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Generator;

use Codeception\Test\Unit;
use ReflectionClass;
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
     * @dataProvider provideMergeTestCases
     *
     * @param array $transferDefinitions
     * @param string $testDescription
     *
     * @return void
     */
    public function testMergeShouldHandleDifferentTransferCombinations(array $transferDefinitions, string $testDescription): void
    {
        // Arrange
        $helper = new TransferDefinitionMergerHelper();
        $expected = [
            'ItemMetadata' => $helper->getExpectedMergedItemMetadata(),
            'ItemMetadataTransfer' => $helper->getExpectedMergedItemMetadataTransfer(),
        ];

        $transferConfig = new TransferConfig();
        $merger = new TransferDefinitionMerger($transferConfig, $this->getMessengerMock());

        // Act
        $result = $merger->merge($transferDefinitions);

        // Assert
        $this->assertCount(2, $result, $testDescription);
        $this->assertEquals($expected, $result, $testDescription);
    }

    /**
     * @return void
     */
    public function testMergePreservesOriginalTransferNames(): void
    {
        // Arrange
        $helper = new TransferDefinitionMergerHelper();
        $transferDefinitions = [
            $helper->getItemMetadata(),
            $helper->getItemMetadataTransfer(),
        ];

        $transferConfig = new TransferConfig();
        $merger = new TransferDefinitionMerger($transferConfig);

        // Act
        $result = $merger->merge($transferDefinitions);

        // Assert
        $this->assertArrayHasKey('ItemMetadata', $result);
        $this->assertArrayHasKey('ItemMetadataTransfer', $result);

        $this->assertEquals('ItemMetadata', $result['ItemMetadata']['name']);
        $this->assertEquals('ItemMetadataTransfer', $result['ItemMetadataTransfer']['name']);
    }

    /**
     * @return void
     */
    public function testGetNormalizedTransferNameShouldRemoveTransferSuffix(): void
    {
        // Arrange
        $transferConfig = new TransferConfig();
        $merger = new TransferDefinitionMerger($transferConfig);
        $reflectionClass = new ReflectionClass(TransferDefinitionMerger::class);

        $getNormalizedTransferNameMethod = $reflectionClass->getMethod('getNormalizedTransferName');
        $getNormalizedTransferNameMethod->setAccessible(true);

        // Act & Assert
        $this->assertEquals('ItemMetadata', $getNormalizedTransferNameMethod->invoke($merger, 'ItemMetadataTransfer'));
        $this->assertEquals('ItemMetadata', $getNormalizedTransferNameMethod->invoke($merger, 'ItemMetadata'));
        $this->assertEquals('User', $getNormalizedTransferNameMethod->invoke($merger, 'UserTransfer'));
        $this->assertEquals('User', $getNormalizedTransferNameMethod->invoke($merger, 'User'));
    }

    /**
     * @return void
     */
    public function testHasTransferSuffixShouldDetectTransferSuffix(): void
    {
        // Arrange
        $transferConfig = new TransferConfig();
        $merger = new TransferDefinitionMerger($transferConfig);
        $reflectionClass = new ReflectionClass(TransferDefinitionMerger::class);

        $hasTransferSuffixMethod = $reflectionClass->getMethod('hasTransferSuffix');
        $hasTransferSuffixMethod->setAccessible(true);

        // Act & Assert
        $this->assertTrue($hasTransferSuffixMethod->invoke($merger, 'ItemMetadataTransfer'));
        $this->assertTrue($hasTransferSuffixMethod->invoke($merger, 'UserTransfer'));
        $this->assertFalse($hasTransferSuffixMethod->invoke($merger, 'ItemMetadata'));
        $this->assertFalse($hasTransferSuffixMethod->invoke($merger, 'Transfer'));
        $this->assertFalse($hasTransferSuffixMethod->invoke($merger, 'UserTrans'));
    }

    /**
     * @dataProvider provideTransferNameValidationSettings
     *
     * @param bool $isTransferNameValidated
     *
     * @return void
     */
    public function testMergeBehaviorWithDifferentTransferNameValidationSettings(bool $isTransferNameValidated): void
    {
        // Arrange
        $helper = new TransferDefinitionMergerHelper();
        $transferDefinitions = [
            $helper->getItemMetadata(),
            $helper->getItemMetadataTransfer(),
        ];

        $transferConfig = $this->createTransferConfigMock($isTransferNameValidated);
        $merger = new TransferDefinitionMerger($transferConfig);

        // Act
        $result = $merger->merge($transferDefinitions);

        // Assert
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('ItemMetadata', $result);
        $this->assertArrayHasKey('ItemMetadataTransfer', $result);
        $this->assertEquals('ItemMetadata', $result['ItemMetadata']['name']);
        $this->assertEquals('ItemMetadataTransfer', $result['ItemMetadataTransfer']['name']);
    }

    /**
     * @return array<string, array<bool>>
     */
    protected function provideTransferNameValidationSettings(): array
    {
        return [
            'transfer name validation enabled' => [true],
            'transfer name validation disabled' => [false],
        ];
    }

    /**
     * @return void
     */
    public function testMergeResultsAreIdenticalRegardlessOfTransferNameValidationSetting(): void
    {
        // Arrange
        $helper = new TransferDefinitionMergerHelper();
        $transferDefinitions = [
            $helper->getItemMetadata(),
            $helper->getItemMetadataTransfer(),
        ];

        $transferConfigEnabled = $this->createTransferConfigMock(true);
        $transferConfigDisabled = $this->createTransferConfigMock(false);

        $mergerEnabled = new TransferDefinitionMerger($transferConfigEnabled);
        $mergerDisabled = new TransferDefinitionMerger($transferConfigDisabled);

        // Act
        $resultEnabled = $mergerEnabled->merge($transferDefinitions);
        $resultDisabled = $mergerDisabled->merge($transferDefinitions);

        // Assert - Results should be identical
        $this->assertEquals(
            $resultEnabled,
            $resultDisabled,
            'Transfer merge results should be identical regardless of isTransferNameValidated() setting',
        );
    }

    /**
     * @param bool $isTransferNameValidated
     *
     * @return \Spryker\Zed\Transfer\TransferConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createTransferConfigMock(bool $isTransferNameValidated): TransferConfig
    {
        $transferConfig = $this->getMockBuilder(TransferConfig::class)
            ->onlyMethods(['isTransferNameValidated'])
            ->getMock();

        $transferConfig->expects($this->any())
            ->method('isTransferNameValidated')
            ->willReturn($isTransferNameValidated);

        return $transferConfig;
    }

    /**
     * @return array
     */
    protected function provideMergeTestCases(): array
    {
        $helper = new TransferDefinitionMergerHelper();

        return [
            'with and without suffix' => [
                [
                    $helper->getItemMetadata(),
                    $helper->getItemMetadataTransfer(),
                ],
                'Should merge transfers with and without Transfer suffix',
            ],
            'reversed order' => [
                [
                    $helper->getItemMetadataTransfer(),
                    $helper->getItemMetadata(),
                ],
                'Should handle reversed order of transfer definitions',
            ],
        ];
    }

    /**
     * @return \Symfony\Component\Console\Logger\ConsoleLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMessengerMock(): ConsoleLogger
    {
        return $this->getMockBuilder(ConsoleLogger::class)->disableOriginalConstructor()->getMock();
    }
}
