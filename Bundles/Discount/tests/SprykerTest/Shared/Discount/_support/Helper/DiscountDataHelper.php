<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Discount\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\DiscountConfiguratorBuilder;
use Generated\Shared\DataBuilder\DiscountGeneralBuilder;
use Generated\Shared\DataBuilder\DiscountVoucherBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Propel\Runtime\Propel;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class DiscountDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $override
     * @param array $discountAmounts
     *
     * @return \Generated\Shared\Transfer\DiscountGeneralTransfer
     */
    public function haveDiscount(array $override = [], array $discountAmounts = [])
    {
        $discountFacade = $this->getDiscountFacade();

        $discountConfigurator = (new DiscountConfiguratorBuilder($override))
            ->withDiscountGeneral((new DiscountGeneralBuilder())->withStoreRelation())
            ->withDiscountCondition()
            ->withDiscountCalculator()
            ->build();

        $discountCalculatorTransfer = $discountConfigurator->getDiscountCalculator();

        foreach ($discountAmounts as $price) {
            $moneyValueTransfer = (new MoneyValueBuilder($price))->build();
            $discountCalculatorTransfer->addMoneyValue($moneyValueTransfer);
        }

        $discountConditionTransfer = $discountConfigurator->getDiscountCondition();
        $discountConditionTransfer->setMinimumItemAmount(1);

        $this->debugSection('Discount', $discountConfigurator->toArray());
        $discountId = $discountFacade->saveDiscount($discountConfigurator);
        $this->debugSection('Discount Id', $discountId);

        $cleanupModule = $this->getDataCleanupHelper();
        $cleanupModule->_addCleanup(function () use ($discountId) {
            $this->debug('Deleting Discount: ' . $discountId);
            $this->getDiscountQuery()->queryDiscount()->findByIdDiscount($discountId)->delete();
        });

        return $discountConfigurator->getDiscountGeneral();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\DiscountVoucherTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveGeneratedVoucherCodes(array $override = [])
    {
        $discountFacade = $this->getDiscountFacade();
        $discountVoucherTransfer = (new DiscountVoucherBuilder($override))->build();

        $discountFacade->saveVoucherCodes($discountVoucherTransfer);

        return $discountVoucherTransfer;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    private function getDiscountFacade()
    {
        return $this->getLocator()->discount()->facade();
    }

    /**
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainer
     */
    private function getDiscountQuery()
    {
        return $this->getLocator()->discount()->queryContainer();
    }

    /**
     * @return void
     */
    public function resetCurrentDiscounts()
    {
        $discounts = SpyDiscountQuery::create()->find();
        Propel::disableInstancePooling();
        foreach ($discounts as $discountEntity) {
            $discountEntity->setIsActive(false);
            $discountEntity->save();
        }
    }
}
