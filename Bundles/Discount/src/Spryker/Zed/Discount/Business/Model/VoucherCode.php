<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;

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
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function releaseUsedCodes(array $codes)
    {
        $voucherEntityList = $this->discountQueryContainer->queryVoucherPoolByVoucherCodes($codes)->find();

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
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function useCodes(array $codes)
    {
        $voucherEntityList = $this->discountQueryContainer->queryVoucherPoolByVoucherCodes($codes)->find();

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
