<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString\Specification;

use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaDataProvider;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;

class MetaDataProviderTest extends \PHPUnit_Framework_TestCase
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
    public function testGetAcceptedTypesByFieldNameShouldReturnAcceptedDateTypesForGivenPlugin()
    {
        $decisionRulePluginMock = $this->createDecisionRulePluginMock();

        $fieldName = 'sample field';
        $decisionRulePluginMock->expects($this->once())
            ->method('getFieldName')
            ->willReturn($fieldName);

        $decisionRulePluginMock->expects($this->once())
            ->method('acceptedDataTypes')
            ->willReturn([ComparatorOperators::TYPE_INTEGER]);

        $metaDataProvider = $this->createMetaDataProvider($decisionRulePluginMock);

        $acceptedDataTypes = $metaDataProvider->getAcceptedTypesByFieldName($fieldName);

        $this->assertCount(1, $acceptedDataTypes);
        $this->assertEquals(ComparatorOperators::TYPE_INTEGER, $acceptedDataTypes[0]);
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
            ->willReturn([ComparatorOperators::TYPE_INTEGER]);

        $comparatorOperatorsMock = $this->createComparatorOperatorsMock();

        $comparatorExpression = '=';
        $comparatorOperatorsMock->expects($this->once())
            ->method('getOperatorExpressionsByTypes')
            ->with([ComparatorOperators::TYPE_INTEGER])
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
     * @param DecisionRulePluginInterface|null $decisionRulePluginMock
     * @param ComparatorOperators|null $comparatorOperators
     * @param LogicalComparators|null $logicalComparatorsMock
     * @return MetaDataProvider
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
                $decisionRulePluginMock
            ],
            $comparatorOperators,
            $logicalComparatorsMock
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|DecisionRulePluginInterface
     */
    protected function createDecisionRulePluginMock()
    {
        return $this->getMock(DecisionRulePluginInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ComparatorOperators
     */
    protected function createComparatorOperatorsMock()
    {
        return $this->getMockBuilder(ComparatorOperators::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|LogicalComparators
     */
    protected function createLogicalComparatorsMock()
    {
        return $this->getMock(LogicalComparators::class);
    }

}
