<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use SprykerFeature\Shared\Discount\Transfer\DiscountVoucher;

/**
 * Class DiscountVoucherManager
 * @package SprykerFeature\Zed\Discount\Business\Model
 */
class DiscountVoucherWriter extends AbstractWriter
{
    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param DiscountVoucher $discountVoucherTransfer
     * @return mixed
     */
    public function create(DiscountVoucher $discountVoucherTransfer)
    {
        $discountVoucherEntity = $this->locator->discount()->entitySpyDiscountVoucher();
        $discountVoucherEntity->fromArray($discountVoucherTransfer->toArray());
        $discountVoucherEntity->save();

        return $discountVoucherEntity;
    }

    /**
     * @param DiscountVoucher $discountVoucherTransfer
     * @return array|mixed|\SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function update(DiscountVoucher $discountVoucherTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountVoucherEntity = $queryContainer
            ->queryDiscountVoucher()
            ->findPk($discountVoucherTransfer->getIdDiscountVoucher());
        $discountVoucherEntity->fromArray($discountVoucherTransfer->toArray());
        $discountVoucherEntity->save();

        return $discountVoucherEntity;
    }
}
