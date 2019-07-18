<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Specification;

use Codeception\Test\Unit;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Specification
 * @group MetaDataProviderTest
 * Add your own group annotations below this line
 */
class MetaDataProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testGetAvailableFieldsShouldReturnFieldProvidedByPlugins()
    {
        $fieldName = 'sample field';
        $decisionRulePluginMock = $this->createDecisionRulePluginMock();

        $decisionRulePluginMock->expects($this->once())
            ->method('getFieldName')
            ->willReturn($fieldName);

        $metaDataProvider = $this->createMetaDataProvider($decisionRulePluginMock);

        $availableFields = $metaDataProvider->getAvailableFields();

        $this->assertCount(1, $availableFields);
        $this->assertEquals($fieldName, $availableFields[0]);
    }

    /**
     * @return void
     */
    public function testIsFieldAvailableReturnsTrueIfFieldIsAvailable()
    {
        // Assign
        $fieldName = 'sample field';
        $expectedResult = true;

        $decisionRulePluginMock = $this->createDecisionRulePluginMock();
        $decisionRulePluginMock
            ->expects($this->any())
            ->method('getFieldName')
            ->willReturn($fieldName);

        // Act
        $actualResult = $this->createMetaDataProvider($decisionRulePluginMock)->isFieldAvailable($fieldName);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testIsFieldAvailableReturnsFalseIfFieldIsNotAvailable()
    {
        // Assign
        $fieldName = 'sample field';
        $otherFieldName = 'sample field 2';
        $expectedResult = false;

        $decisionRulePluginMock = $this->createDecisionRulePluginMock();
        $decisionRulePluginMock
            ->expects($this->any())
            ->method('getFieldName')
            ->willReturn($fieldName);

        // Act
        $actualResult = $this->createMetaDataProvider($decisionRulePluginMock)->isFieldAvailable($otherFieldName);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testGetAcceptedTypesByFieldNameShouldReturnAcceptedDateTypesForGivenPlugin()
    {
        $decisionRulePluginMock = $this->createDecisionRulePluginMock();

        $fieldName = 'sample field';
        $decisionRulePluginMock->expects($this->once())
            ->method('getFieldName')
            ->willReturn($fieldName);

        $decisionRulePluginMock->expects($this->once())
            ->method('acceptedDataTypes')
            ->willReturn([ComparatorOperators::TYPE_NUMBER]);

        $metaDataProvider = $this->createMetaDataProvider($decisionRulePluginMock);

        $acceptedDataTypes = $metaDataProvider->getAcceptedTypesByFieldName($fieldName);

        $this->assertCount(1, $acceptedDataTypes);
        $this->assertEquals(ComparatorOperators::TYPE_NUMBER, $acceptedDataTypes[0]);
    }

    /**
     * @return void
     */
    public function testAvailableOperatorExpressionsShouldReturnAllOperatorExpressions()
    {
        $decisionRulePluginMock = $this->createDecisionRulePluginMock();

        $fieldName = 'sample field';
        $decisionRulePluginMock->expects($this->once())
            ->method('getFieldName')
            ->willReturn($fieldName);

        $decisionRulePluginMock->expects($this->once())
            ->method('acceptedDataTypes')
            ->willReturn([ComparatorOperators::TYPE_NUMBER]);

        $comparatorOperatorsMock = $this->createComparatorOperatorsMock();

        $comparatorExpression = '=';
        $comparatorOperatorsMock->expects($this->once())
            ->method('getOperatorExpressionsByTypes')
            ->with([ComparatorOperators::TYPE_NUMBER])
            ->willReturn([$comparatorExpression]);

        $metaDataProvider = $this->createMetaDataProvider($decisionRulePluginMock, $comparatorOperatorsMock);

        $comparatorExpressions = $metaDataProvider->getAvailableOperatorExpressionsForField($fieldName);

        $this->assertCount(1, $comparatorExpressions);
        $this->assertEquals($comparatorExpression, $comparatorExpressions[0]);
    }

    /**
     * @return void
     */
    public function testGetLogicalComparatorsShouldReturnListOfOperatorsProvidedByComparator()
    {
        $logicalComparatorsMock = $this->createLogicalComparatorsMock();
        $logicalComparatorsMock->expects($this->once())
            ->method('getLogicalOperators')
            ->willReturn(['and']);

        $metaDataProvider = $this->createMetaDataProvider(null, null, $logicalComparatorsMock);
        $logicalOperators = $metaDataProvider->getLogicalComparators();

        $this->assertCount(1, $logicalOperators);
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface|null $decisionRulePluginMock
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators|null $comparatorOperators
     * @param \Spryker\Zed\Discount\Business\QueryString\LogicalComparators|null $logicalComparatorsMock
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider
     */
    protected function createMetaDataProvider($decisionRulePluginMock = null, $comparatorOperators = null, $logicalComparatorsMock = null)
    {
        if (!isset($decisionRulePluginMock)) {
            $decisionRulePluginMock = $this->createDecisionRulePluginMock();
        }

        if (!isset($comparatorOperators)) {
            $comparatorOperators = $this->createComparatorOperatorsMock();
        }

        if (!isset($logicalComparatorsMock)) {
            $logicalComparatorsMock = $this->createLogicalComparatorsMock();
        }

        return new MetaDataProvider(
            [
                $decisionRulePluginMock,
            ],
            $comparatorOperators,
            $logicalComparatorsMock
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface
     */
    protected function createDecisionRulePluginMock()
    {
        return $this->getMockBuilder(DecisionRulePluginInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected function createComparatorOperatorsMock()
    {
        return $this->getMockBuilder(ComparatorOperators::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\LogicalComparators
     */
    protected function createLogicalComparatorsMock()
    {
        return $this->getMockBuilder(LogicalComparators::class)->getMock();
    }
}
