<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer;

use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface;
use Symfony\Component\Form\DataTransformerInterface;

class PriceTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(PriceProductScheduleGuiToMoneyFacadeInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param mixed $value
     *
     * @return float|null
     */
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        return $this->moneyFacade->convertIntegerToDecimal($value);
    }

    /**
     * @param mixed $value
     *
     * @return int|null
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return null;
        }

        return $this->moneyFacade->convertDecimalToInteger($value);
    }
}
