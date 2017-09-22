<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\Transformer;

use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;
use Symfony\Component\Form\DataTransformerInterface;

class MoneyAmountTransformer implements DataTransformerInterface
{

    /**
     * @var \Spryker\Zed\Money\Business\MoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Money\Business\MoneyFacadeInterface $moneyFacade
     */
    public function __construct(MoneyFacadeInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param mixed $value The value in the original representation
     *
     * @return float|mixed The value in the transformed representation
     */
    public function transform($value)
    {
         if (!$this->isValueSet($value)) {
             return $value;
         }

         $value->setNetAmount($this->moneyFacade->convertIntegerToDecimal($value->getNetAmount()));
         $value->setGrossAmount($this->moneyFacade->convertIntegerToDecimal($value->getGrossAmount()));
         return $value;
    }

    /**
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     */
    public function reverseTransform($value)
    {
         if (!$this->isValueSet($value)) {
             return $value;
         }

        $value->setNetAmount($this->moneyFacade->convertDecimalToInteger((float)$value->getNetAmount()));
        $value->setGrossAmount($this->moneyFacade->convertDecimalToInteger((float)$value->getGrossAmount()));

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isValueSet($value)
    {
        return ($value && ($value->getGrossAmount() || $value->getNetAmount()));
    }

}
