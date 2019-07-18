<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use DateTime;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConditionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\DiscountFacadeInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class DiscountFormDataProvider extends BaseDiscountFormDataProvider
{
    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacadeInterface $discountFacade
     */
    public function __construct(DiscountFacadeInterface $discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param int|null $idDiscount
     *
     * @return mixed
     */
    public function getData($idDiscount = null)
    {
        if ($idDiscount === null) {
            return $this->createDefaultDiscountConfiguratorTransfer();
        }

        return $this->discountFacade->findHydratedDiscountConfiguratorByIdDiscount($idDiscount);
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function createDefaultDiscountConfiguratorTransfer(): DiscountConfiguratorTransfer
    {
        $discountConfiguratorTransfer = new DiscountConfiguratorTransfer();

        $discountGeneralTransfer = $this->createDiscountGeneralTransferDefaults();
        $discountConfiguratorTransfer->setDiscountGeneral($discountGeneralTransfer);

        $calculatedDiscountTransfer = $this->createDiscountCalculatorTransfer();
        $discountConfiguratorTransfer->setDiscountCalculator($calculatedDiscountTransfer);

        $discountConditionTransfer = $this->createDiscountConditionTransfer();
        $discountConfiguratorTransfer->setDiscountCondition($discountConditionTransfer);

        return $discountConfiguratorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountGeneralTransfer
     */
    protected function createDiscountGeneralTransferDefaults()
    {
        $discountGeneralTransfer = new DiscountGeneralTransfer();
        $discountGeneralTransfer->setIsExclusive(false);
        $discountGeneralTransfer->setValidFrom(new DateTime());
        $discountGeneralTransfer->setValidTo(new DateTime());

        return $discountGeneralTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountCalculatorTransfer
     */
    protected function createDiscountCalculatorTransfer()
    {
        $discountCalculatorTransfer = new DiscountCalculatorTransfer();
        $discountCalculatorTransfer->setCalculatorPlugin(DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED);
        $discountCalculatorTransfer->setCollectorStrategyType(DiscountConstants::DISCOUNT_COLLECTOR_STRATEGY_QUERY_STRING);

        return $discountCalculatorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountConditionTransfer
     */
    protected function createDiscountConditionTransfer(): DiscountConditionTransfer
    {
        $discountConditionTransfer = new DiscountConditionTransfer();
        $discountConditionTransfer->setMinimumItemAmount(DiscountConfig::DEFAULT_MINIMUM_ITEM_AMOUNT);

        return $discountConditionTransfer;
    }
}
