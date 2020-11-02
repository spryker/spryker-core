<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\MySql;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\MySql\JsonMapper;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PropelQueryBuilder
 * @group Persistence
 * @group QueryBuilder
 * @group JsonMapper
 * @group MySql
 * @group JsonMapperTest
 * Add your own group annotations below this line
 */
class JsonMapperTest extends Unit
{
    protected const RULE_FIELD = 'RULE_FIELD';
    protected const RULE_VALUE = 'RULE_VALUE';
    protected const OPERATOR = 'OPERATOR';
    protected const ATTRIBUTE_NAME = 'ATTRIBUTE_NAME';

    /**
     * @return void
     */
    public function testGetValueShouldReturnMappedJsonValue(): void
    {
        // Arrange
        $jsonMapper = new JsonMapper();
        $ruleSetTransfer = $this->getPropelQueryBuilderRuleSetTransfer();
        $operator = $this->getOperatorMock();

        $expectedValue = sprintf(
            "JSON_EXTRACT(%s, '$.%s') %s '%s'",
            static::RULE_FIELD,
            static::ATTRIBUTE_NAME,
            static::OPERATOR,
            static::RULE_VALUE
        );

        // Act
        $mapperValue = $jsonMapper->getValue($ruleSetTransfer, $operator, static::ATTRIBUTE_NAME);

        // Assert
        $this->assertSame($expectedValue, $mapperValue);
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    protected function getPropelQueryBuilderRuleSetTransfer(): PropelQueryBuilderRuleSetTransfer
    {
        return (new PropelQueryBuilderRuleSetTransfer())
            ->setField(static::RULE_FIELD)
            ->setValue(static::RULE_VALUE);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface
     */
    protected function getOperatorMock(): OperatorInterface
    {
        $operatorMock = $this->getMockForAbstractClass(OperatorInterface::class);

        $operatorMock->expects($this->once())
            ->method('getValue')
            ->willReturn(static::RULE_VALUE);
        $operatorMock->expects($this->once())
            ->method('getOperator')
            ->willReturn(static::OPERATOR);

        return $operatorMock;
    }
}
