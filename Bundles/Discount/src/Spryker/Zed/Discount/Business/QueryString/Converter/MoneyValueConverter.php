<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Converter;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface;

class MoneyValueConverter implements MoneyValueConverterInterface
{
    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface $moneyFacade
     */
    public function __construct(DiscountToMoneyInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    public function convertDecimalToCent(ClauseTransfer $clauseTransfer): ClauseTransfer
    {
        $clonedClauseTransfer = clone $clauseTransfer;

        if ($clonedClauseTransfer->getOperator() === IsNotIn::EXPRESSION ||
            $clonedClauseTransfer->getOperator() === IsIn::EXPRESSION) {
            $this->convertListPrice($clonedClauseTransfer);
        } else {
            $this->convertSinglePrice($clonedClauseTransfer);
        }

        return $clonedClauseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return void
     */
    protected function convertListPrice(ClauseTransfer $clauseTransfer)
    {
        $prices = explode(ComparatorOperators::LIST_DELIMITER, $clauseTransfer->getValue());
        $amountInCentsList = '';
        foreach ($prices as $price) {
            if ($amountInCentsList) {
                $amountInCentsList .= ComparatorOperators::LIST_DELIMITER;
            }

            $amountInCents = $this->moneyFacade->convertDecimalToInteger($this->formatValue($price));
            $amountInCentsList .= $amountInCents;
        }

        $clauseTransfer->setValue($amountInCentsList);
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return void
     */
    protected function convertSinglePrice(ClauseTransfer $clauseTransfer)
    {
        $amountInCents = $this->moneyFacade->convertDecimalToInteger(
            $this->formatValue($clauseTransfer->getValue())
        );
        $clauseTransfer->setValue($amountInCents);
    }

    /**
     * @param string $value
     *
     * @return float
     */
    protected function formatValue($value)
    {
        return (float)str_replace(',', '.', trim($value));
    }
}
