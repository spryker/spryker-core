<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;

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
     * @param array $codes
     *
     * @return bool
     */
    public function enableCodes(array $codes)
    {
        $voucherEntityList = $this->discountQueryContainer->queryVoucherPoolByVoucherCodes($codes)->find();

        if (0 === count($voucherEntityList)) {
            return false;
        }

        foreach ($voucherEntityList as $voucherEntity) {
            if ($voucherEntity->getIsActive()) {
                continue;
            }

            $voucherEntity->setIsActive(true);
            $voucherEntity->save();
        }

        return true;
    }
    
}
