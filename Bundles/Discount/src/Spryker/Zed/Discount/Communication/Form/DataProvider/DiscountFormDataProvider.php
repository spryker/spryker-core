<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConditionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\DiscountFacadeInterface;
use Spryker\Zed\Discount\Communication\Form\DiscountForm;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToLocaleFacadeInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class DiscountFormDataProvider extends BaseDiscountFormDataProvider
{
    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacadeInterface $discountFacade
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        DiscountFacadeInterface $discountFacade,
        DiscountToLocaleFacadeInterface $localeFacade
    ) {
        $this->discountFacade = $discountFacade;
        $this->localeFacade = $localeFacade;
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
     * @return array<string, string>
     */
    public function getOptions(): array
    {
        return [
            'data_class' => DiscountConfiguratorTransfer::class,
            DiscountForm::OPTION_LOCALE => $this->localeFacade->getCurrentLocaleName(),
        ];
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
        return (new DiscountGeneralTransfer())->setIsExclusive(false);
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
