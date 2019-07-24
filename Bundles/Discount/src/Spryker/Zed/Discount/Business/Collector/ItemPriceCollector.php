<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface;

class ItemPriceCollector extends BaseCollector implements CollectorInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected $comparators;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface
     */
    protected $currencyConverter;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparators
     * @param \Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface $currencyConverter
     */
    public function __construct(
        ComparatorOperatorsInterface $comparators,
        MoneyValueConverterInterface $currencyConverter
    ) {
        $this->comparators = $comparators;
        $this->currencyConverter = $currencyConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $clonedClauseTransfer = clone $clauseTransfer;

        $this->currencyConverter->convertDecimalToCent($clonedClauseTransfer);

        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->comparators->compare($clonedClauseTransfer, $itemTransfer->getUnitPrice()) === false) {
                continue;
            }

            $discountableItems[] = $this->createDiscountableItemForItemTransfer($itemTransfer);
        }

        return $discountableItems;
    }
}
