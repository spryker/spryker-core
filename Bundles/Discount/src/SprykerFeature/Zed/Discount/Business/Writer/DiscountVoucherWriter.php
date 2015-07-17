<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\VoucherTransfer;
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
     * @param VoucherTransfer $discountVoucherTransfer
     *
     * @return mixed
     */
    public function create(VoucherTransfer $discountVoucherTransfer)
    {
        $discountVoucherEntity = $this->locator->discount()->entitySpyDiscountVoucher();
        $discountVoucherEntity->fromArray($discountVoucherTransfer->toArray());
        $discountVoucherEntity->save();

        return $discountVoucherEntity;
    }

    /**
     * @param VoucherTransfer $discountVoucherTransfer
     *
     * @throws PropelException
     *
     * @return array|mixed|SpyDiscountVoucher
     */
    public function update(VoucherTransfer $discountVoucherTransfer)
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
