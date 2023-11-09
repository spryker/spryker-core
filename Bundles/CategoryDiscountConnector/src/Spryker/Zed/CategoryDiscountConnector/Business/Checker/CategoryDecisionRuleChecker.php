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
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToDiscountFacadeInterface
     */
    protected CategoryDiscountConnectorToDiscountFacadeInterface $discountFacade;

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Business\Reader\ProductCategoryReaderInterface
     */
    protected ProductCategoryReaderInterface $productCategoryReader;

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Business\Reader\CategoryReaderInterface
     */
    protected CategoryReaderInterface $categoryReader;

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
        $productCategoryTransfersGroupedByIdProductAbstract = $this->productCategoryReader->getProductCategoriesGroupedByIdProductAbstract($quoteTransfer);
        $categoryKeysGroupedByIdCategoryNode = $this->categoryReader->getCategoryKeysGroupedByIdCategoryNode($productCategoryTransfersGroupedByIdProductAbstract);

        $productCategoryTransfers = $productCategoryTransfersGroupedByIdProductAbstract[$itemTransfer->getIdProductAbstractOrFail()] ?? [];

        $categoryKeys = [];
        foreach ($productCategoryTransfers as $productCategoryTransfer) {
            $categoryTransfer = $productCategoryTransfer->getCategoryOrFail();
            $categoryKeys[] = $categoryKeysGroupedByIdCategoryNode[$categoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail()]
                ?? [$categoryTransfer->getCategoryKeyOrFail()];
        }

        $categoryKeys = array_unique(array_merge(...$categoryKeys));

        return $this->isSatisfiedBy($clauseTransfer, $categoryKeys);
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
}
