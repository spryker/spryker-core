<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Transformer;

use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface;
use Symfony\Component\Form\DataTransformerInterface;

class CurrencyAmountTransformer implements DataTransformerInterface
{

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface $moneyPlugin
     */
    public function __construct(DiscountToMoneyInterface $moneyPlugin)
    {
        $this->moneyFacade = $moneyPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer|mixed $value The value in the original representation
     *
     * @return float|mixed The value in the transformed representation
     */
    public function transform($value)
    {
         if (!$this->isValueSet($value)) {
             return $value;
         }

         $value->setAmount($this->moneyFacade->convertIntegerToDecimal($value->getAmount()));
         return $value;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer|mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     */
    public function reverseTransform($value)
    {
         if (!$this->isValueSet($value)) {
             return $value;
         }

         $value->setAmount($this->moneyFacade->convertDecimalToInteger((float)$value->getAmount()));
         return $value;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer $value
     *
     * @return bool
     */
    protected function isValueSet(DiscountMoneyAmountTransfer $value)
    {
        return ($value && $value->getAmount());
    }

}
