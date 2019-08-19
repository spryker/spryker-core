<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint;

use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface;
use Symfony\Component\Validator\Constraint;

class PriceProductScheduleUniqueConstraint extends Constraint
{
    protected const VALIDATION_MESSAGE = 'An identical scheduled price already exist for this product.';

    public const OPTION_PRICE_PRODUCT_SCHEDULE_FACADE = 'priceProductScheduleFacade';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
     */
    protected $priceProductScheduleFacade;

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
     */
    public function getPriceProductScheduleFacade(): PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
    {
        return $this->priceProductScheduleFacade;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::VALIDATION_MESSAGE;
    }
}
