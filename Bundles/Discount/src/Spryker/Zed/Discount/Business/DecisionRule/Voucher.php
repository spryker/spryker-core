<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Spryker\Zed\Kernel\Business\ModelResult;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;

class Voucher
{

    const REASON_VOUCHER_CODE_NOT_AVAILABLE = 'discount.voucher_code.not_valid';
    const REASON_VOUCHER_CODE_NOT_ACTIVE = 'discount.voucher_code.not_active';
    const REASON_VOUCHER_CODE_POOL_MISSING = 'discount.voucher_code.pool_not_set';
    const REASON_VOUCHER_CODE_POOL_NOT_ACTIVE = 'discount.voucher_code.pool_not_active';
    const REASON_VOUCHER_CODE_LIMIT_REACHED = 'discount.voucher_code.usage_limit.reached';

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
            $result->addError(self::REASON_VOUCHER_CODE_LIMIT_REACHED);
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
        if ($discountVoucherEntity->getMaxNumberOfUses() > 0 &&
            $discountVoucherEntity->getNumberOfUses() >= $discountVoucherEntity->getMaxNumberOfUses()) {
            return false;
        }

        return true;
    }

}
