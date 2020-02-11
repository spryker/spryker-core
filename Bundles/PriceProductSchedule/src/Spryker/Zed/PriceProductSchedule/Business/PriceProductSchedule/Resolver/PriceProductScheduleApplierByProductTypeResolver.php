<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\AbstractProductPriceProductScheduleApplierInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\ConcreteProductPriceProductScheduleApplierInterface;

class PriceProductScheduleApplierByProductTypeResolver implements PriceProductScheduleApplierByProductTypeResolverInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\AbstractProductPriceProductScheduleApplierInterface
     */
    protected $abstractProductPriceProductScheduleApplier;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\ConcreteProductPriceProductScheduleApplierInterface
     */
    protected $concreteProductPriceProductScheduleApplier;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\AbstractProductPriceProductScheduleApplierInterface $abstractProductPriceProductScheduleApplier
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\ConcreteProductPriceProductScheduleApplierInterface $concreteProductPriceProductScheduleApplier
     */
    public function __construct(
        AbstractProductPriceProductScheduleApplierInterface $abstractProductPriceProductScheduleApplier,
        ConcreteProductPriceProductScheduleApplierInterface $concreteProductPriceProductScheduleApplier
    ) {
        $this->abstractProductPriceProductScheduleApplier = $abstractProductPriceProductScheduleApplier;
        $this->concreteProductPriceProductScheduleApplier = $concreteProductPriceProductScheduleApplier;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    public function applyPriceProductScheduleByProductType(PriceProductScheduleTransfer $priceProductScheduleTransfer): void
    {
        $priceProductScheduleTransfer->requirePriceProduct();
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();

        if ($priceProductTransfer->getIdProduct() !== null) {
            $this->concreteProductPriceProductScheduleApplier->applyScheduledPrices($priceProductScheduleTransfer);

            return;
        }

        $this->abstractProductPriceProductScheduleApplier->applyScheduledPrices($priceProductScheduleTransfer);
    }
}
