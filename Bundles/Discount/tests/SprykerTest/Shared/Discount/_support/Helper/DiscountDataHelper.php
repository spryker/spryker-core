<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Discount\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\DiscountBuilder;
use Generated\Shared\DataBuilder\DiscountConfiguratorBuilder;
use Generated\Shared\DataBuilder\DiscountGeneralBuilder;
use Generated\Shared\DataBuilder\DiscountVoucherBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Spryker\Zed\Discount\Business\DiscountFacadeInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerTest\Shared\Propel\Helper\InstancePoolingHelperTrait;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class DiscountDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;
    use InstancePoolingHelperTrait;

    /**
     * @param array $override
     * @param array $discountAmounts
     *
     * @return \Generated\Shared\Transfer\DiscountGeneralTransfer
     */
    public function haveDiscount(array $override = [], array $discountAmounts = []): DiscountGeneralTransfer
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
        $cleanupModule->_addCleanup(function () use ($discountId): void {
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
    private function getDiscountFacade(): DiscountFacadeInterface
    {
        return $this->getLocator()->discount()->facade();
    }

    /**
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainer
     */
    private function getDiscountQuery(): DiscountQueryContainer
    {
        return $this->getLocator()->discount()->queryContainer();
    }

    /**
     * @return void
     */
    public function resetCurrentDiscounts(): void
    {
        $discounts = SpyDiscountQuery::create()->find();
        $this->disableInstancePooling();
        foreach ($discounts as $discountEntity) {
            $discountEntity->setIsActive(false);
            $discountEntity->save();
        }
    }

    /**
     * @param array $seedData
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscount
     */
    public function haveSalesDiscount(array $seedData = []): SpySalesDiscount
    {
        $discountTransfer = (new DiscountBuilder($seedData))->build();
        $data = array_merge($discountTransfer->toArray(false), $seedData);
        $salesDiscountEntity = new SpySalesDiscount();
        $salesDiscountEntity->fromArray($data);
        $salesDiscountEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($salesDiscountEntity): void {
            $this->debug('Deleting Discount: ' . $salesDiscountEntity->getIdSalesDiscount());
            $salesDiscountEntity->delete();
        });

        return $salesDiscountEntity;
    }
}
