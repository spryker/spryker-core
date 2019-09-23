<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\ComparatorInterface;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group ComparatorOperatorsTest
 * Add your own group annotations below this line
 */
class ComparatorOperatorsTest extends Unit
{
    /**
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
                ComparatorOperators::TYPE_NUMBER,
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
     * @return void
     */
    public function testComparatorExpressionsByTypesShouldReturnAllOperatorsMatchingDataType()
    {
        $equalComparatorMock = $this->createComparatorMock();
        $equalComparatorMock->expects($this->once())
            ->method('getAcceptedTypes')
            ->willReturn([
                ComparatorOperators::TYPE_NUMBER,
                ComparatorOperators::TYPE_STRING,
            ]);
        $equalComparatorMock->expects($this->once())
            ->method('getExpression')
            ->willReturn('=');

        $moreComparatorMock = $this->createComparatorMock();
        $moreComparatorMock->expects($this->once())
            ->method('getAcceptedTypes')
            ->willReturn([
                ComparatorOperators::TYPE_NUMBER,
            ]);

        $moreComparatorMock->expects($this->once())
            ->method('getExpression')
            ->willReturn('>');

        $lessComparatorMock = $this->createComparatorMock();
        $lessComparatorMock->expects($this->once())
            ->method('getAcceptedTypes')
            ->willReturn([
                ComparatorOperators::TYPE_LIST,
            ]);

        $comparatorOperators = $this->createComparatorOperators([$equalComparatorMock, $moreComparatorMock, $lessComparatorMock]);

        $expressions = $comparatorOperators->getOperatorExpressionsByTypes([ComparatorOperators::TYPE_NUMBER]);

        $this->assertCount(2, $expressions);
        $this->assertEquals('=', $expressions[0]);
        $this->assertEquals('>', $expressions[1]);
    }

    /**
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
     * @return void
     */
    public function testGetCompoundComparatorExpressions()
    {
        $combinedOperator = 'combined operator';
        $equalComparatorMock = $this->createComparatorMock();
        $equalComparatorMock->expects($this->once())
            ->method('getExpression')
            ->willReturn($combinedOperator);

        $moreComparatorMock = $this->createComparatorMock();
        $moreComparatorMock->expects($this->once())
            ->method('getExpression')
            ->willReturn('>');

        $comparatorOperators = $this->createComparatorOperators([$equalComparatorMock, $moreComparatorMock]);

        $combinedOperators = $comparatorOperators->getCompoundComparatorExpressions();

        $this->assertCount(2, $combinedOperators);
    }

    /**
     * @return void
     */
    public function testIsValidComparatorWhenValidShouldReturnTrue()
    {
        $equalComparatorMock = $this->createComparatorMock();
        $equalComparatorMock->expects($this->once())
            ->method('accept')
            ->willReturn(true);

        $comparatorOperators = $this->createComparatorOperators([$equalComparatorMock]);

        $clauseTransfer = new ClauseTransfer();

        $isValid = $comparatorOperators->isExistingComparator($clauseTransfer);

        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIsValidComparatorWhenInValidShouldReturnFalse()
    {
        $equalComparatorMock = $this->createComparatorMock();
        $equalComparatorMock->expects($this->once())
            ->method('accept')
            ->willReturn(false);

        $comparatorOperators = $this->createComparatorOperators([$equalComparatorMock]);

        $clauseTransfer = new ClauseTransfer();

        $isValid = $comparatorOperators->isExistingComparator($clauseTransfer);

        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testCompareWhenUsingMatchAllIdentifierShouldAlwaysReturnFalse()
    {
        $comparatorOperators = $this->createComparatorOperators([]);

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue(ComparatorOperators::MATCH_ALL_IDENTIFIER);

        $isValid = $comparatorOperators->compare($clauseTransfer, 'value');

        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueIsNotProvidedShouldReturnFalse()
    {
        $comparatorOperators = $this->createComparatorOperators([]);

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue(ComparatorOperators::MATCH_ALL_IDENTIFIER);

        $isValid = $comparatorOperators->compare($clauseTransfer, '');

        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueIsNumericZeroProvidedShouldReturnTrue()
    {
        $comparatorOperators = $this->createComparatorOperators([]);

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue(ComparatorOperators::MATCH_ALL_IDENTIFIER);

        $isValid = $comparatorOperators->compare($clauseTransfer, 0);

        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testWhenNoneOfComparatorsAcceptsClauseShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $equalComparatorMock = $this->createComparatorMock();
        $equalComparatorMock->expects($this->once())
            ->method('accept')
            ->willReturn(false);

        $comparatorOperators = $this->createComparatorOperators([$equalComparatorMock]);

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('not existing');

        $isValid = $comparatorOperators->compare($clauseTransfer, 'value');

        $this->assertFalse($isValid);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\Comparator\ComparatorInterface[] $comparators
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected function createComparatorOperators(array $comparators)
    {
        return new ComparatorOperators($comparators);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\Comparator\ComparatorInterface
     */
    protected function createComparatorMock()
    {
        return $this->getMockBuilder(ComparatorInterface::class)->getMock();
    }
}
