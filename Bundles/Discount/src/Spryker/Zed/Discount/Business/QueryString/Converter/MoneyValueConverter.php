<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Converter;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn;

class MoneyValueConverter implements MoneyValueConverterInterface
{

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManagerInterface
     */
    protected $currencyManager;

    /**
     * @param \Spryker\Shared\Library\Currency\CurrencyManagerInterface $currencyManger
     */
    public function __construct(CurrencyManagerInterface $currencyManger)
    {
        $this->currencyManager = $currencyManger;
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return void
     */
    public function convertDecimalToCent(ClauseTransfer $clauseTransfer)
    {
        if ($clauseTransfer->getOperator() === IsNotIn::EXPRESSION ||
            $clauseTransfer->getOperator() === IsIn::EXPRESSION) {
            $this->convertListPrice($clauseTransfer);
        } else {
            $this->convertSinglePrice($clauseTransfer);
        }
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

            $amountInCents = $this->currencyManager->convertDecimalToCent($this->formatValue($price));
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
        $amountInCents = $this->currencyManager->convertDecimalToCent(
            $this->formatValue($clauseTransfer->getValue())
        );
        $clauseTransfer->setValue($amountInCents);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function formatValue($value)
    {
        return str_replace(',', '.', trim($value));
    }

}
