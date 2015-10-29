<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;

class VoucherCode implements VoucherCodeInterface
{

    /**
     * @var DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param DiscountQueryContainerInterface $discountQueryContainer
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
     * @param SpyDiscountVoucher $discountVoucherEntity
     */
    protected function incrementNumberOfUses(SpyDiscountVoucher $discountVoucherEntity)
    {
        $numberOfUses = $discountVoucherEntity->getNumberOfUses();
        $discountVoucherEntity->setNumberOfUses(++$numberOfUses);
    }

    /**
     * @param SpyDiscountVoucher $discountVoucherEntity
     */
    protected function decrementNumberOfUses(SpyDiscountVoucher $discountVoucherEntity)
    {
        $numberOfUses = $discountVoucherEntity->getNumberOfUses();
        $discountVoucherEntity->setNumberOfUses(--$numberOfUses);
    }

    /**
     * @param SpyDiscountVoucher $voucherEntity
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
     * @param SpyDiscountVoucher $discountVoucherEntity
     */
    protected function saveDiscountVoucherEntity(SpyDiscountVoucher $discountVoucherEntity)
    {
        $discountVoucherEntity->save();
    }

}
