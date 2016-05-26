<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Voucher;

use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class VoucherCode implements VoucherCodeInterface
{

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @param string[] $codes
     *
     * @return bool
     */
    public function releaseUsedCodes(array $codes)
    {
        $voucherEntityList = $this->discountQueryContainer
            ->queryVoucherPoolByVoucherCodes($codes)
            ->find();

        if (count($voucherEntityList) === 0) {
            return false;
        }

        foreach ($voucherEntityList as $discountVoucherEntity) {
            if (!$this->isVoucherWithCounter($discountVoucherEntity)) {
                continue;
            }

            $this->decrementNumberOfUses($discountVoucherEntity);
            $this->saveDiscountVoucherEntity($discountVoucherEntity);
        }

        return true;
    }

    /**
     * @param string[] $codes
     *
     * @return bool
     */
    public function useCodes(array $codes)
    {
        $voucherEntityList = $this->discountQueryContainer
            ->queryVoucherPoolByVoucherCodes($codes)
            ->find();

        if (count($voucherEntityList) === 0) {
            return false;
        }

        foreach ($voucherEntityList as $discountVoucherEntity) {
            if (!$discountVoucherEntity->getIsActive()) {
                continue;
            }

            if (!$this->isVoucherWithCounter($discountVoucherEntity)) {
                continue;
            }

            $this->incrementNumberOfUses($discountVoucherEntity);
            $this->saveDiscountVoucherEntity($discountVoucherEntity);
        }

        return true;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $discountVoucherEntity
     *
     * @return void
     */
    protected function incrementNumberOfUses(SpyDiscountVoucher $discountVoucherEntity)
    {
        $numberOfUses = $discountVoucherEntity->getNumberOfUses();
        $discountVoucherEntity->setNumberOfUses(++$numberOfUses);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $discountVoucherEntity
     *
     * @return void
     */
    protected function decrementNumberOfUses(SpyDiscountVoucher $discountVoucherEntity)
    {
        $numberOfUses = $discountVoucherEntity->getNumberOfUses();
        $discountVoucherEntity->setNumberOfUses(--$numberOfUses);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $voucherEntity
     *
     * @return bool
     */
    protected function isVoucherWithCounter(SpyDiscountVoucher $voucherEntity)
    {
        $maxNumberOfUses = $voucherEntity->getMaxNumberOfUses();

        if ($maxNumberOfUses !== null) {
            return true;
        }

        return false;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $discountVoucherEntity
     *
     * @return void
     */
    protected function saveDiscountVoucherEntity(SpyDiscountVoucher $discountVoucherEntity)
    {
        $discountVoucherEntity->save();
    }

}
