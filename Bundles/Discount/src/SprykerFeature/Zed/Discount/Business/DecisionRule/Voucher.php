<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\DecisionRule;

use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class Voucher
{

    const REASON_VOUCHER_CODE_NOT_AVAILABLE = 'Voucher code is not valid';

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
     * @param int $idDiscountVoucherPool
     *
     * @return ModelResult
     */
    public function isUsable($code, $idDiscountVoucherPool)
    {
        $result = new ModelResult();

        $voucher = $this->discountQueryContainer
            ->queryVoucher($code)
            ->filterByFkDiscountVoucherPool($idDiscountVoucherPool)
            ->findOne();

        if (!$voucher) {
            return $result->addError(self::REASON_VOUCHER_CODE_NOT_AVAILABLE);
        }

        if (!$voucher->getIsActive()) {
            return $result->addError(self::REASON_VOUCHER_CODE_NOT_AVAILABLE);
        }

        if (!$voucherPool = $voucher->getVoucherPool()) {
            return $result->addError(self::REASON_VOUCHER_CODE_NOT_AVAILABLE);
        }

        if (!$voucherPool->getIsActive()) {
            return $result->addError(self::REASON_VOUCHER_CODE_NOT_AVAILABLE);
        }

        return $result;
    }


    /**
     * @param string $code
     *
     * @return ModelResult
     */
    public function isCodeUsable($code)
    {
        $result = new ModelResult();

        $voucher = $this->discountQueryContainer
            ->queryVoucher($code)
            ->findOne();

        if (!$voucher) {
            return $result->addError(self::REASON_VOUCHER_CODE_NOT_AVAILABLE);
        }

        if (!$voucher->getIsActive()) {
            return $result->addError(self::REASON_VOUCHER_CODE_NOT_AVAILABLE);
        }

        if (!$voucherPool = $voucher->getVoucherPool()) {
            return $result->addError(self::REASON_VOUCHER_CODE_NOT_AVAILABLE);
        }

        if (!$voucherPool->getIsActive()) {
            return $result->addError(self::REASON_VOUCHER_CODE_NOT_AVAILABLE);
        }

        return $result;
    }

}
