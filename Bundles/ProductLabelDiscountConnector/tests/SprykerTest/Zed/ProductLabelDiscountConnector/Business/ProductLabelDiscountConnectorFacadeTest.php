<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelDiscountConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductLabelTransfer;
use SprykerTest\Shared\Testify\Helper\AssertArraySubsetTrait;
use SprykerTest\Zed\ProductLabelDiscountConnector\ProductLabelDiscountConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelDiscountConnector
 * @group Business
 * @group Facade
 * @group ProductLabelDiscountConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductLabelDiscountConnectorFacadeTest extends Unit
{
    use AssertArraySubsetTrait;

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_IS_IN = 'is in';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_IS_NOT_IN = 'is not in';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\Comparator\Equal::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_EQUAL = '=';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_STRING
     *
     * @var string
     */
    protected const TYPE_STRING = 'string';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_LIST
     *
     * @var string
     */
    protected const TYPE_LIST = 'list';

    /**
     * @var string
     */
    protected const PRODUCT_LABEL_NAME_1 = 'test';

    /**
     * @var string
     */
    protected const PRODUCT_LABEL_NAME_2 = 'test2';

    /**
     * @var \SprykerTest\Zed\ProductLabelDiscountConnector\ProductLabelDiscountConnectorBusinessTester
     */
    protected ProductLabelDiscountConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindAllLabelsShoulsReturnListOfExistingLabels(): void
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => 'label 1',
        ]);
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => 'label 2',
        ]);

        // Act
        $actualLabels = $this->tester->getFacade()->findAllLabels();

        // Assert
        $expectedLabels = [
            'label 1' => 'label 1',
            'label 2' => 'label 2',
        ];
        $this->assertArraySubset($expectedLabels, $actualLabels, 'Missing expected list of labels.');
    }

    /**
     * @return void
     */
    public function testIsProductLabelSatisfiedByShouldReturnTrueWhenLabelIsPresent(): void
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
        ]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $quoteTransfer = $this->tester->createQuoteTransfer([$productConcreteTransfer]);
        $clauseTransfer = $this->tester->createClauseTransfer(
            [static::TYPE_STRING],
            $productLabelTransfer->getName(),
            static::EXPRESSION_EQUAL,
        );

        // Act
        $isSatisfied = $this->tester->getFacade()->isProductLabelSatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()[0],
            $clauseTransfer,
        );

        // Assert
        $this->assertTrue($isSatisfied, 'Quote should have been satisfied by product label.');
    }

    /**
     * @return void
     */
    public function testIsProductLabelSatisfiedByShouldReturnFalseWhenLabelIsNotPresent(): void
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
        ]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $quoteTransfer = $this->tester->createQuoteTransfer([$productConcreteTransfer]);
        $clauseTransfer = $this->tester->createClauseTransfer(
            [static::TYPE_STRING],
            $productLabelTransfer->getName(),
            static::EXPRESSION_EQUAL,
        );

        // Act
        $isSatisfied = $this->tester->getFacade()->isProductLabelSatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()[0],
            $clauseTransfer,
        );

        // Assert
        $this->assertFalse($isSatisfied, 'Quote should not have been satisfied by product label.');
    }

    /**
     * @return void
     */
    public function testCollectByProductLabelShouldCollectAllItemsMatchingLabel(): void
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
        ]);

        $productConcreteTransfer1 = $this->tester->haveProduct();
        $productConcreteTransfer2 = $this->tester->haveProduct();
        $productConcreteTransfer3 = $this->tester->haveProduct();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer1->getFkProductAbstract(),
        );

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer2->getFkProductAbstract(),
        );

        $quoteTransfer = $this->tester->createQuoteTransfer([
            $productConcreteTransfer1,
            $productConcreteTransfer2,
            $productConcreteTransfer3,

        ]);
        $clauseTransfer = $this->tester->createClauseTransfer(
            [static::TYPE_STRING],
            $productLabelTransfer->getName(),
            static::EXPRESSION_EQUAL,
        );

        // Act
        $collected = $this->tester->getFacade()->collectByProductLabel($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(2, $collected, 'Number of collected items should match expected number.');
    }

    /**
     * @return void
     */
    public function testCollectByExclusiveProductLabelShouldCollectItemsMatchingOnlyExclusiveLabel(): void
    {
        // Arrange
        $productLabelTransfer1 = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
            ProductLabelTransfer::POSITION => 1,
        ]);
        $productLabelTransfer2 = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
            ProductLabelTransfer::IS_EXCLUSIVE,
            ProductLabelTransfer::POSITION => 2,
        ]);
        $productLabelTransfer3 = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
            ProductLabelTransfer::POSITION => 3,
        ]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer1->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract(),
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract(),
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer3->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $quoteTransfer = $this->tester->createQuoteTransfer([$productConcreteTransfer]);
        $clauseTransfer = $this->tester->createClauseTransfer(
            [static::TYPE_STRING],
            $productLabelTransfer1->getName(),
            static::EXPRESSION_EQUAL,
        );

        // Act
        $collected = $this->tester->getFacade()->collectByProductLabel($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(1, $collected, 'Number of collected items should match expected number.');
    }

    /**
     * @dataProvider getProductLabelListClauseDataProvider
     *
     * @param array<array<string, mixed>> $productLabelsData
     * @param string $clauseValue
     * @param string $clauseOperator
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testIsProductLabelSatisfiedByListClause(
        array $productLabelsData,
        string $clauseValue,
        string $clauseOperator,
        bool $expectedResult
    ): void {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProductWithProductLabels($productLabelsData);
        $quoteTransfer = $this->tester->createQuoteTransfer([$productConcreteTransfer]);
        $clauseTransfer = $this->tester->createClauseTransfer([static::TYPE_LIST], $clauseValue, $clauseOperator);

        // Act
        $isSatisfied = $this->tester->getFacade()->isProductLabelSatisfiedByListClause(
            $quoteTransfer->getItems()->getIterator()->current(),
            $clauseTransfer,
        );

        // Arrange
        $this->assertSame($expectedResult, $isSatisfied);
    }

    /**
     * @dataProvider getProductLabelListClauseDataProvider
     *
     * @param array<array<string, mixed>> $productLabelsData
     * @param string $clauseValue
     * @param string $clauseOperator
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testGetDiscountableItemsCollection(
        array $productLabelsData,
        string $clauseValue,
        string $clauseOperator,
        bool $expectedResult
    ): void {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProductWithProductLabels($productLabelsData);
        $quoteTransfer = $this->tester->createQuoteTransfer([$productConcreteTransfer]);
        $clauseTransfer = $this->tester->createClauseTransfer([static::TYPE_LIST], $clauseValue, $clauseOperator);

        // Act
        $discountableItemTransfers = $this->tester->getFacade()->getDiscountableItemsCollection(
            $quoteTransfer,
            $clauseTransfer,
        );

        // Arrange
        $this->assertSame($expectedResult, $discountableItemTransfers !== []);
    }

    /**
     * @return array<string, array<array<array<string, mixed>>|string|bool>>
     */
    public function getProductLabelListClauseDataProvider(): array
    {
        return [
            'Should return true when at least one product label is in the condition list.' => [
                [
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_1,
                    ],
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_2,
                    ],
                ],
                static::PRODUCT_LABEL_NAME_1,
                static::EXPRESSION_IS_IN,
                true,
            ],
            'Should return false when no product labels are in the condition list.' => [
                [
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_1,
                    ],
                ],
                static::PRODUCT_LABEL_NAME_2,
                static::EXPRESSION_IS_IN,
                false,
            ],
            'Should return false when a product has an exclusive product label that is not in the condition list.' => [
                [
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_1,
                    ],
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_2,
                        ProductLabelTransfer::IS_EXCLUSIVE => true,
                    ],
                ],
                static::PRODUCT_LABEL_NAME_1,
                static::EXPRESSION_IS_IN,
                false,
            ],
            'Should return false when a product does not have product labels.' => [
                [],
                static::PRODUCT_LABEL_NAME_1,
                static::EXPRESSION_IS_IN,
                false,
            ],
            'Should return true when no product labels are in the condition list.' => [
                [
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_1,
                    ],
                ],
                static::PRODUCT_LABEL_NAME_2,
                static::EXPRESSION_IS_NOT_IN,
                true,
            ],
            'Should return true when a product has an exclusive product label that is not in the condition list.' => [
                [
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_1,
                    ],
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_2,
                        ProductLabelTransfer::IS_EXCLUSIVE => true,
                    ],
                ],
                static::PRODUCT_LABEL_NAME_1,
                static::EXPRESSION_IS_NOT_IN,
                true,
            ],
            'Should return true when a product does not have product labels.' => [
                [],
                static::PRODUCT_LABEL_NAME_1,
                static::EXPRESSION_IS_NOT_IN,
                true,
            ],
            'Should return false when at least one product label is in the condition list.' => [
                [
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_1,
                    ],
                    [
                        ProductLabelTransfer::VALID_FROM => null,
                        ProductLabelTransfer::VALID_TO => null,
                        ProductLabelTransfer::NAME => static::PRODUCT_LABEL_NAME_2,
                    ],
                ],
                static::PRODUCT_LABEL_NAME_1,
                static::EXPRESSION_IS_NOT_IN,
                false,
            ],
        ];
    }
}
