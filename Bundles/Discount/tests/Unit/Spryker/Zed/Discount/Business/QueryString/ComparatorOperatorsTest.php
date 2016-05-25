<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\Comparator\ComparatorInterface;

class ComparatorOperatorsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return void
     */
    public function testCompareWhenComparatorEvaluatesToTrueShouldReturnTrue()
    {
        $equalComparatorMock = $this->createComparatorMock();
        $equalComparatorMock->expects($this->once())
            ->method('compare')
            ->willReturn(true);

        $equalComparatorMock->expects($this->once())
            ->method('accept')
            ->willReturn(true);

        $equalComparatorMock->expects($this->once())
            ->method('getAcceptedTypes')
            ->willReturn([
                ComparatorOperators::TYPE_STRING,
            ]);

        $comparatorOperators = $this->createComparatorOperators([$equalComparatorMock]);

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setField('sku');
        $clauseTransfer->setValue('123');
        $clauseTransfer->setOperator('=');
        $clauseTransfer->setAcceptedTypes([
            ComparatorOperators::TYPE_STRING,
        ]);

        $isEqual = $comparatorOperators->compare($clauseTransfer, '123');

        $this->assertTrue($isEqual);
    }

    /**
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return void
     */
    public function testCompareWhenComparatorNotFoundShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $comparatorOperators = $this->createComparatorOperators([]);

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setField('sku');
        $clauseTransfer->setValue('123');
        $clauseTransfer->setOperator('=');
        $clauseTransfer->setAcceptedTypes([
            ComparatorOperators::TYPE_STRING,
        ]);

        $comparatorOperators->compare($clauseTransfer, '123');
    }

    /**
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return void
     */
    public function testCompareWhenComparatorCannotHandleTypeShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $equalComparatorMock = $this->createComparatorMock();

        $equalComparatorMock->expects($this->once())
            ->method('accept')
            ->willReturn(true);

        $equalComparatorMock->expects($this->once())
            ->method('getAcceptedTypes')
            ->willReturn([
                ComparatorOperators::TYPE_INTEGER,
            ]);

        $comparatorOperators = $this->createComparatorOperators([$equalComparatorMock]);

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setField('sku');
        $clauseTransfer->setValue('123');
        $clauseTransfer->setOperator('=');
        $clauseTransfer->setAcceptedTypes([
            ComparatorOperators::TYPE_STRING,
        ]);

        $comparatorOperators->compare($clauseTransfer, '123');
    }

    /**
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return void
     */
    public function testComparatorExpressionsByTypesShouldReturnAllOperatorsMatchingDataType()
    {
        $equalComparatorMock = $this->createComparatorMock();
        $equalComparatorMock->expects($this->once())
            ->method('getAcceptedTypes')
            ->willReturn([
                ComparatorOperators::TYPE_INTEGER,
                ComparatorOperators::TYPE_STRING
            ]);
        $equalComparatorMock->expects($this->once())
            ->method('getExpression')
            ->willReturn('=');

        $moreComparatorMock = $this->createComparatorMock();
        $moreComparatorMock->expects($this->once())
            ->method('getAcceptedTypes')
            ->willReturn([
                ComparatorOperators::TYPE_INTEGER,
            ]);

        $moreComparatorMock->expects($this->once())
            ->method('getExpression')
            ->willReturn('>');

        $comparatorOperators = $this->createComparatorOperators([$equalComparatorMock, $moreComparatorMock]);

        $expressions = $comparatorOperators->getOperatorExpressionsByTypes([ComparatorOperators::TYPE_INTEGER]);

        $this->assertCount(2, $expressions);
        $this->assertEquals('=', $expressions[0]);
        $this->assertEquals('>', $expressions[1]);
    }

    /**
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return void
     */
    public function testGetAvailableComparatorShouldReturnAllAvailableExpressions()
    {
        $equalComparatorMock = $this->createComparatorMock();
        $equalComparatorMock->expects($this->once())
            ->method('getExpression')
            ->willReturn('=');

        $moreComparatorMock = $this->createComparatorMock();
        $moreComparatorMock->expects($this->once())
            ->method('getExpression')
            ->willReturn('>');

        $comparatorOperators = $this->createComparatorOperators([$equalComparatorMock, $moreComparatorMock]);

        $expressions = $comparatorOperators->getAvailableComparatorExpressions();

        $this->assertCount(2, $expressions);
        $this->assertEquals('=', $expressions[0]);
        $this->assertEquals('>', $expressions[1]);
    }

    /**
     * @param array|\Spryker\Zed\Discount\Business\QueryString\Comparator\ComparatorInterface[] $comparators
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected function createComparatorOperators(array $comparators)
    {
        return new ComparatorOperators($comparators);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\Comparator\ComparatorInterface
     */
    protected function createComparatorMock()
    {
        return $this->getMock(ComparatorInterface::class);
    }

}
