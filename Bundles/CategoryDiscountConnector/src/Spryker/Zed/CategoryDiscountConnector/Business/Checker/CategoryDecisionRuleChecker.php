<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business\Checker;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\CategoryReaderInterface;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\ProductCategoryReaderInterface;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToDiscountFacadeInterface;

class CategoryDecisionRuleChecker implements CategoryDecisionRuleCheckerInterface
{
    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::LIST_DELIMITER
     *
     * @var string
     */
    protected const LIST_DELIMITER = ';';

    /**
     * @var array<int, array<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    protected static $groupedProductCategoryTransfers = [];

    /**
     * @var array<int, array<string>>
     */
    protected static $groupedCategoryKeys = [];

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToDiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Business\Reader\ProductCategoryReaderInterface
     */
    protected $productCategoryReader;

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Business\Reader\CategoryReaderInterface
     */
    protected $categoryReader;

    /**
     * @param \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToDiscountFacadeInterface $discountFacade
     * @param \Spryker\Zed\CategoryDiscountConnector\Business\Reader\ProductCategoryReaderInterface $productCategoryReader
     * @param \Spryker\Zed\CategoryDiscountConnector\Business\Reader\CategoryReaderInterface $categoryReader
     */
    public function __construct(
        CategoryDiscountConnectorToDiscountFacadeInterface $discountFacade,
        ProductCategoryReaderInterface $productCategoryReader,
        CategoryReaderInterface $categoryReader
    ) {
        $this->discountFacade = $discountFacade;
        $this->productCategoryReader = $productCategoryReader;
        $this->categoryReader = $categoryReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCategorySatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer): bool
    {
        $groupedProductCategoryTransfers = $this->getProductCategoriesGroupedByIdProductAbstract($quoteTransfer);
        $groupedCategoryKeys = $this->getCategoryKeysGroupedByIdCategoryNode($groupedProductCategoryTransfers);

        $productCategoryTransfers = $groupedProductCategoryTransfers[$itemTransfer->getIdProductAbstractOrFail()] ?? [];

        foreach ($productCategoryTransfers as $productCategoryTransfer) {
            $categoryTransfer = $productCategoryTransfer->getCategoryOrFail();
            $ascendantCategoryKeys = $groupedCategoryKeys[$categoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail()]
                ?? [$categoryTransfer->getCategoryKeyOrFail()];

            if ($this->isSatisfiedBy($clauseTransfer, $ascendantCategoryKeys)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param array<string> $ascendantCategoryKeys
     *
     * @return bool
     */
    protected function isSatisfiedBy(ClauseTransfer $clauseTransfer, array $ascendantCategoryKeys): bool
    {
        $invertedClause = (new ClauseTransfer())
            ->fromArray($clauseTransfer->toArray(), true)
            ->setValue(implode(static::LIST_DELIMITER, $ascendantCategoryKeys));

        return $this->discountFacade->queryStringCompare($invertedClause, $clauseTransfer->getValueOrFail());
    }

    /**
     * @param array<int, array<\Generated\Shared\Transfer\ProductCategoryTransfer>> $groupedProductCategoryTransfers
     *
     * @return array<int, array<string>>
     */
    protected function getCategoryKeysGroupedByIdCategoryNode(array $groupedProductCategoryTransfers): array
    {
        if (static::$groupedCategoryKeys) {
            return static::$groupedCategoryKeys;
        }

        static::$groupedCategoryKeys = $this->categoryReader
            ->getCategoryKeysGroupedByIdCategoryNode($groupedProductCategoryTransfers);

        return static::$groupedCategoryKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    protected function getProductCategoriesGroupedByIdProductAbstract(QuoteTransfer $quoteTransfer): array
    {
        if (static::$groupedProductCategoryTransfers) {
            return static::$groupedProductCategoryTransfers;
        }

        static::$groupedProductCategoryTransfers = $this->productCategoryReader
            ->getProductCategoriesGroupedByIdProductAbstract($quoteTransfer);

        return static::$groupedProductCategoryTransfers;
    }
}
