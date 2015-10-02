<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\Base\SpyDiscountVoucher;

class VoucherCode implements VoucherCodeInterface
{
    /**
     * @var DiscountQueryContainerInterface
     */
    private $discountQueryContainer;

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
    public function releaseCodes(array $codes)
    {
        $voucherEntityList = $this->discountQueryContainer->queryVoucherPoolByVoucherCodes($codes)->find();

        if (0 === count($voucherEntityList)) {
            return false;
        }

        foreach ($voucherEntityList as $discountVoucherEntity) {
            if ($this->isVoucherWithCounter($discountVoucherEntity)) {
                $this->incrementNumberOfUses($discountVoucherEntity);
            }

            $discountVoucherEntity->setIsActive(true);
            $discountVoucherEntity->save();
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

        if (0 === count($voucherEntityList)) {
            return false;
        }

        foreach ($voucherEntityList as $discountVoucherEntity) {
            if (!$discountVoucherEntity->getIsActive()) {
                continue;
            }

            if ($discountVoucherEntity->getVoucherPool()->isInfinitelyUsable()) {
                continue;
            }

            if ($this->isVoucherWithCounter($discountVoucherEntity)) {
                $this->decrementNumberOfUses($discountVoucherEntity);
                if ($discountVoucherEntity->getNumberOfUses() <= 0) {
                    $discountVoucherEntity->setIsActive(false);
                }
            } else {
                $discountVoucherEntity->setIsActive(false);
            }

            $discountVoucherEntity->save();
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
        $numberOfUses = $voucherEntity->getNumberOfUses();

        if (null !== $numberOfUses) {
            return true;
        }

        return false;

    }

}
