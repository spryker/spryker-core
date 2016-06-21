<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Converter;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn;

class CurrencyConverter implements CurrencyConverterInterface
{

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManagerInterface
     */
    protected $currencyManager;

    /**
     * CurrencyConverter constructor.
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
        $prices = explode(',', $clauseTransfer->getValue());
        $amountInCentsList = '';
        foreach ($prices as $price) {
            if ($amountInCentsList) {
                $amountInCentsList .= ',';
            }

            $amountInCents = $this->currencyManager->convertDecimalToCent($price);
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
        return str_replace(',', '.', $value);
    }

}
