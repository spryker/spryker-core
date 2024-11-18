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
use Generated\Shared\DataBuilder\DiscountMoneyAmountBuilder;
use Generated\Shared\DataBuilder\DiscountVoucherBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountAmount;
use Orm\Zed\Discount\Persistence\SpyDiscountAmountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountStore;
use Orm\Zed\Discount\Persistence\SpyDiscountStoreQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery;
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

        $discountGeneralBuilder = (new DiscountGeneralBuilder($override))
            ->withStoreRelation();

        $discountConfigurator = (new DiscountConfiguratorBuilder($override))
            ->withDiscountGeneral($discountGeneralBuilder)
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
        $discountConfiguratorResponseTransfer = $discountFacade->createDiscount($discountConfigurator);
        $idDiscount = $discountConfiguratorResponseTransfer->getDiscountConfiguratorOrFail()
            ->getDiscountGeneralOrFail()
            ->getIdDiscountOrFail();
        $this->debugSection('Discount Id', $idDiscount);

        $cleanupModule = $this->getDataCleanupHelper();
        $cleanupModule->_addCleanup(function () use ($idDiscount): void {
            $this->debug('Deleting Discount: ' . $idDiscount);
            $this->getDiscountQuery()->queryDiscount()->findByIdDiscount($idDiscount)->delete();
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
     * @param string $name
     * @param bool $isActive
     *
     * @return int
     */
    public function haveDiscountVoucherPool(string $name, bool $isActive = true): int
    {
        $discountVoucherPoolEntity = (new SpyDiscountVoucherPool())
            ->setIsActive($isActive)
            ->setName($name);
        $discountVoucherPoolEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($discountVoucherPoolEntity): void {
            $this->cleanupDiscountVoucherPool($discountVoucherPoolEntity);
        });

        return $discountVoucherPoolEntity->getIdDiscountVoucherPool();
    }

    /**
     * @param array $discountOverride
     * @param int $discountMinimumItemAmount
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function haveDiscountWithMinimumItemAmount(array $discountOverride = [], int $discountMinimumItemAmount = 1): DiscountTransfer
    {
        $discountTransfer = (new DiscountBuilder($discountOverride))->build();

        $discountEntity = (new SpyDiscount())
            ->fromArray($discountTransfer->toArray())
            ->setMinimumItemAmount($discountMinimumItemAmount);
        $discountEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($discountEntity): void {
            $this->cleanupDiscount($discountEntity);
        });

        return $discountTransfer->fromArray($discountEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function haveDiscountStore(StoreTransfer $storeTransfer, DiscountTransfer $discountTransfer): int
    {
        $discountStoreEntity = (new SpyDiscountStore())
            ->setFkStore($storeTransfer->getIdStore())
            ->setFkDiscount($discountTransfer->getIdDiscount());
        $discountStoreEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($discountStoreEntity): void {
            $this->cleanupDiscountStore($discountStoreEntity);
        });

        return $discountStoreEntity->getIdDiscountStore();
    }

    /**
     * @param array $discountAmountOverride
     *
     * @return \Generated\Shared\Transfer\DiscountMoneyAmountTransfer
     */
    public function haveDiscountAmount(array $discountAmountOverride = []): DiscountMoneyAmountTransfer
    {
        $discountMoneyAmountTransfer = (new DiscountMoneyAmountBuilder($discountAmountOverride))->build();

        $discountAmountEntity = (new SpyDiscountAmount())->fromArray($discountMoneyAmountTransfer->toArray());
        $discountAmountEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($discountAmountEntity): void {
            $this->cleanupDiscountAmount($discountAmountEntity);
        });

        return $discountMoneyAmountTransfer->fromArray($discountAmountEntity->toArray(), true);
    }

    /**
     * @param string $voucherCode
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\DiscountVoucherTransfer
     */
    public function haveDiscountVoucher(string $voucherCode, DiscountTransfer $discountTransfer, array $seed = []): DiscountVoucherTransfer
    {
        $discountVoucherTransfer = (new DiscountVoucherBuilder($seed))->build();
        $voucherEntity = (new SpyDiscountVoucher())->fromArray($discountVoucherTransfer->toArray());
        $voucherEntity->setFkDiscountVoucherPool($discountTransfer->getFkDiscountVoucherPool());
        $voucherEntity->setCode($voucherCode);
        $voucherEntity->setIsActive(true);
        $voucherEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($voucherEntity): void {
            $this->debug('Deleting Discount Voucher: ' . $voucherEntity->getIdDiscountVoucher());
            $voucherEntity->delete();
        });

        return (new DiscountVoucherTransfer())
            ->fromArray($voucherEntity->toArray(), true);
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

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $discountVoucherPoolEntity
     *
     * @return void
     */
    protected function cleanupDiscountVoucherPool(SpyDiscountVoucherPool $discountVoucherPoolEntity): void
    {
        SpyDiscountVoucherPoolQuery::create()
            ->filterByIdDiscountVoucherPool($discountVoucherPoolEntity->getIdDiscountVoucherPool())
            ->delete();
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return void
     */
    protected function cleanupDiscount(SpyDiscount $discountEntity): void
    {
        SpyDiscountQuery::create()
            ->filterByIdDiscount($discountEntity->getIdDiscount())
            ->delete();
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountStore $discountStoreEntity
     *
     * @return void
     */
    protected function cleanupDiscountStore(SpyDiscountStore $discountStoreEntity): void
    {
        SpyDiscountStoreQuery::create()
            ->findByIdDiscountStore($discountStoreEntity->getIdDiscountStore())
            ->delete();
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountAmount $discountAmountEntity
     *
     * @return void
     */
    protected function cleanupDiscountAmount(SpyDiscountAmount $discountAmountEntity): void
    {
        SpyDiscountAmountQuery::create()
            ->findByIdDiscountAmount($discountAmountEntity->getIdDiscountAmount())
            ->delete();
    }
}
