<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\DecisionRule;

use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;

class Voucher
{

    const REASON_VOUCHER_CODE_NOT_AVAILABLE = 'Voucher code is not valid.';
    const REASON_VOUCHER_CODE_NOT_ACTIVE = 'Voucher code is not active.';
    const REASON_VOUCHER_CODE_POOL_MISSING = 'Voucher code pool is not set.';
    const REASON_VOUCHER_CODE_POOL_NOT_ACTIVE = 'Voucher code pool is not active.';
    const REASON_VOUCHER_CODE_LIMIT_REACHED = 'Voucher max number of "%d" uses limit is reached.';

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
     * @param string $code
     *
     * @return ModelResult
     */
    public function isUsable($code)
    {
        $discountVoucherEntity = $this->discountQueryContainer
            ->queryVoucher($code)
            ->findOne();

        return $this->validateDiscountVoucher($discountVoucherEntity);
    }

    /**
     * @param SpyDiscountVoucher $discountVoucherEntity
     *
     * @return ModelResult
     */
    protected function validateDiscountVoucher(SpyDiscountVoucher $discountVoucherEntity = null)
    {
        $result = new ModelResult();

        if (!$discountVoucherEntity) {
            return $result->addError(self::REASON_VOUCHER_CODE_NOT_AVAILABLE);
        }

        if (!$discountVoucherEntity->getIsActive()) {
            $result->addError(self::REASON_VOUCHER_CODE_NOT_ACTIVE);
        }

        $voucherPoolEntity = $discountVoucherEntity->getVoucherPool();
        if (!$voucherPoolEntity) {
            return $result->addError(self::REASON_VOUCHER_CODE_POOL_MISSING);
        }

        if (!$voucherPoolEntity->getIsActive()) {
            $result->addError(self::REASON_VOUCHER_CODE_POOL_NOT_ACTIVE);
        }

        if (!$this->isValidNumberOfUses($discountVoucherEntity)) {
            $result->addError(
                sprintf(self::REASON_VOUCHER_CODE_LIMIT_REACHED, $discountVoucherEntity->getMaxNumberOfUses())
            );
        }

        return $result;
    }

    /**
     * @param SpyDiscountVoucher $discountVoucherEntity
     *
     * @return bool
     */
    protected function isValidNumberOfUses(SpyDiscountVoucher $discountVoucherEntity)
    {
        if ($discountVoucherEntity->getMaxNumberOfUses() !== null &&
            $discountVoucherEntity->getNumberOfUses() >= $discountVoucherEntity->getMaxNumberOfUses()) {
            return false;
        }

        return true;
    }

}
