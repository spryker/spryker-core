<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountDiscountVoucherTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;

class DiscountVoucherWriter extends AbstractWriter
{
    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param DiscountDiscountVoucherTransfer $discountVoucherTransfer
     * @return mixed
     */
    public function create(DiscountDiscountVoucherTransfer $discountVoucherTransfer)
    {
        $discountVoucherEntity = $this->locator->discount()->entitySpyDiscountVoucher();
        $discountVoucherEntity->fromArray($discountVoucherTransfer->toArray());
        $discountVoucherEntity->save();

        return $discountVoucherEntity;
    }

    /**
     * @param DiscountDiscountVoucherTransfer $discountVoucherTransfer
     * @return array|mixed|SpyDiscountVoucher
     * @throws PropelException
     */
    public function update(DiscountDiscountVoucherTransfer $discountVoucherTransfer)
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
