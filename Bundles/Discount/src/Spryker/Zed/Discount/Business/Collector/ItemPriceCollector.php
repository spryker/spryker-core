<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;

class ItemPriceCollector extends BaseCollector implements CollectorInterface
{

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected $comparators;

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected $currencyManager;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparators
     * @param \Spryker\Shared\Library\Currency\CurrencyManagerInterface $currencyManager
     */
    public function __construct(
        ComparatorOperatorsInterface $comparators,
        CurrencyManagerInterface $currencyManager
    ) {
        $this->comparators = $comparators;
        $this->currencyManager = $currencyManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $amountInCents = $this->currencyManager->convertDecimalToCent($clauseTransfer->getValue());
            $clauseTransfer->setValue($amountInCents);
            if ($this->comparators->compare($clauseTransfer, $itemTransfer->getUnitGrossPrice()) === false) {
                continue;
            }

            $discountableItems[] = $this->createDiscountableItemTransfer(
                $itemTransfer->getUnitGrossPrice(),
                $itemTransfer->getQuantity(),
                $itemTransfer->getCalculatedDiscounts()
            );
        }

        return $discountableItems;
    }

}
