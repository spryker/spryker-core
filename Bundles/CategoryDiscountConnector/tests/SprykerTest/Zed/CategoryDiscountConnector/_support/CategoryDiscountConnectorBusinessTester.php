<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryDiscountConnector;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use ReflectionClass;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\CategoryReader;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\ProductCategoryReader;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\CategoryDiscountConnector\Business\CategoryDiscountConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\CategoryDiscountConnector\PHPMD)
 */
class CategoryDiscountConnectorBusinessTester extends Actor
{
    use _generated\CategoryDiscountConnectorBusinessTesterActions;

    /**
     * @see \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_LIST
     *
     * @var string
     */
    protected const TYPE_LIST = 'list';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::LIST_DELIMITER
     *
     * @var string
     */
    protected const LIST_DELIMITER = ';';

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryById(int $idCategory): ?CategoryTransfer
    {
        return $this->getLocator()
            ->category()
            ->facade()
            ->findCategory((new CategoryCriteriaTransfer())->setIdCategory($idCategory));
    }

    /**
     * @param string $operator
     * @param array<\Generated\Shared\Transfer\CategoryTransfer> $categoryTransfers
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    public function createClauseTransfer(string $operator, array $categoryTransfers): ClauseTransfer
    {
        $categoryKeys = [];

        foreach ($categoryTransfers as $categoryTransfer) {
            $categoryKeys[] = $categoryTransfer->getCategoryKey();
        }

        return (new ClauseTransfer())
            ->setOperator($operator)
            ->setValue(implode(static::LIST_DELIMITER, $categoryKeys))
            ->setAcceptedTypes([static::TYPE_LIST]);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(CategoryTransfer $categoryTransfer): QuoteTransfer
    {
        $firstProductTransfer = $this->haveProduct();
        $secondProductTransfer = $this->haveProduct();

        $this->assignProductToCategory($categoryTransfer->getIdCategory(), $firstProductTransfer->getFkProductAbstract());
        $this->assignProductToCategory($categoryTransfer->getIdCategory(), $secondProductTransfer->getFkProductAbstract());

        return (new QuoteBuilder())
            ->withItem([ItemTransfer::ID_PRODUCT_ABSTRACT => $firstProductTransfer->getFkProductAbstract()])
            ->withItem([ItemTransfer::ID_PRODUCT_ABSTRACT => $secondProductTransfer->getFkProductAbstract()])
            ->build();
    }

    /**
     * @return void
     */
    public function cleanRuleCheckerStaticProperties(): void
    {
        $reflectedClass = new ReflectionClass(ProductCategoryReader::class);

        $productCategoryTransfersGroupedByIdProductAbstract = $reflectedClass->getProperty('productCategoryTransfersGroupedByIdProductAbstract');
        $productCategoryTransfersGroupedByIdProductAbstract->setAccessible(true);
        $productCategoryTransfersGroupedByIdProductAbstract->setValue([]);

        $reflectedClass = new ReflectionClass(CategoryReader::class);
        $categoryKeysGroupedByIdCategoryNode = $reflectedClass->getProperty('categoryKeysGroupedByIdCategoryNode');
        $categoryKeysGroupedByIdCategoryNode->setAccessible(true);
        $categoryKeysGroupedByIdCategoryNode->setValue([]);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function createProductCategoryCollectionTransfer(CategoryTransfer $categoryTransfer, QuoteTransfer $quoteTransfer): ProductCategoryCollectionTransfer
    {
        $productCategoryCollectionTransfer = new ProductCategoryCollectionTransfer();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productCategoryCollectionTransfer->addProductCategory(
                (new ProductCategoryTransfer())
                    ->setCategory($categoryTransfer)
                    ->setFkCategory($categoryTransfer->getIdCategory())
                    ->setFkProductAbstract($itemTransfer->getIdProductAbstract()),
            );
        }

        return $productCategoryCollectionTransfer;
    }
}
