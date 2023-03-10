<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Business\DecisionRule;

use ArrayObject;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductLabelCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelConditionsTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToDiscountInterface;
use Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelFacadeInterface;

class ProductLabelListDecisionRule implements ProductLabelDecisionRuleInterface
{
    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::LIST_DELIMITER
     *
     * @var string
     */
    protected const LIST_DELIMITER = ';';

    /**
     * @var string
     */
    protected const NO_LABEL_COMPARISON_VALUE = '';

    /**
     * @var \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelFacadeInterface
     */
    protected ProductLabelDiscountConnectorToProductLabelFacadeInterface $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToDiscountInterface
     */
    protected ProductLabelDiscountConnectorToDiscountInterface $discountFacade;

    /**
     * @param \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToDiscountInterface $discountFacade
     */
    public function __construct(
        ProductLabelDiscountConnectorToProductLabelFacadeInterface $productLabelFacade,
        ProductLabelDiscountConnectorToDiscountInterface $discountFacade
    ) {
        $this->productLabelFacade = $productLabelFacade;
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(ItemTransfer $currentItemTransfer, ClauseTransfer $clauseTransfer): bool
    {
        $productLabelCollectionTransfer = $this->getProductLabelCollection($currentItemTransfer);

        if (!$productLabelCollectionTransfer->getProductLabels()->count()) {
            return $this->discountFacade->queryStringCompare($clauseTransfer, static::NO_LABEL_COMPARISON_VALUE);
        }

        $productLabelNames = $this->extractProductLabelNamesFromProductLabels($productLabelCollectionTransfer);

        return $this->discountFacade->queryStringCompare(
            $clauseTransfer,
            implode(static::LIST_DELIMITER, $productLabelNames),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelCollectionTransfer
     */
    protected function getProductLabelCollection(ItemTransfer $itemTransfer): ProductLabelCollectionTransfer
    {
        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())
            ->setProductLabelConditions(
                (new ProductLabelConditionsTransfer())
                    ->setIsActive(true)
                    ->addProductAbstractId($itemTransfer->getIdProductAbstractOrFail()),
            )
            ->setSortCollection(new ArrayObject([
                (new SortTransfer())->setField(ProductLabelTransfer::IS_EXCLUSIVE)->setIsAscending(false),
                (new SortTransfer())->setField(ProductLabelTransfer::POSITION)->setIsAscending(true),
            ]));

        return $this->productLabelFacade->getProductLabelCollection($productLabelCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelCollectionTransfer $productLabelCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractProductLabelNamesFromProductLabels(ProductLabelCollectionTransfer $productLabelCollectionTransfer): array
    {
        $productLabelNames = [];
        foreach ($productLabelCollectionTransfer->getProductLabels() as $productLabelTransfer) {
            $productLabelNames[] = $productLabelTransfer->getNameOrFail();

            if ($productLabelTransfer->getIsExclusive()) {
                break;
            }
        }

        return $productLabelNames;
    }
}
