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
     * @return int
     */
    public function releaseUsedCodes(array $codes)
    {
        $voucherEntityList = $this->discountQueryContainer
            ->queryVoucherPoolByVoucherCodes($codes)
            ->find();

        if (count($voucherEntityList) === 0) {
            return 0;
        }

        $updatedCodes = 0;
        foreach ($voucherEntityList as $discountVoucherEntity) {
            $this->decrementNumberOfUses($discountVoucherEntity);
            $affectedRows = $this->saveDiscountVoucherEntity($discountVoucherEntity);

            if ($affectedRows > 0) {
                $updatedCodes++;
            }
        }

        return $updatedCodes;
    }

    /**
     * @param string[] $codes
     *
     * @return int
     */
    public function useCodes(array $codes)
    {
        $voucherEntityList = $this->discountQueryContainer
            ->queryVoucherPoolByVoucherCodes($codes)
            ->find();

        if (count($voucherEntityList) === 0) {
            return 0;
        }

        $updatedCodes = 0;
        foreach ($voucherEntityList as $discountVoucherEntity) {
            if (!$discountVoucherEntity->getIsActive()) {
                continue;
            }

            $this->incrementNumberOfUses($discountVoucherEntity);
            $affectedRows = $this->saveDiscountVoucherEntity($discountVoucherEntity);

            if ($affectedRows > 0) {
                $updatedCodes++;
            }
        }

        return $updatedCodes;
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
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $discountVoucherEntity
     *
     * @return int
     */
    protected function saveDiscountVoucherEntity(SpyDiscountVoucher $discountVoucherEntity)
    {
        return $discountVoucherEntity->save();
    }

}
