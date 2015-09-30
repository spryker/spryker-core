<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;

class DiscountVoucherPoolWriter extends AbstractWriter
{

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return SpyDiscountVoucherPool
     */
    public function create(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        $discountVoucherPoolEntity = new SpyDiscountVoucherPool();
        $discountVoucherPoolEntity->fromArray($discountVoucherPoolTransfer->toArray());
        $discountVoucherPoolEntity->save();

        return $discountVoucherPoolEntity;
    }

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @throws PropelException
     *
     * @return SpyDiscountVoucherPool
     */
    public function update(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountVoucherPoolEntity = $queryContainer->queryDiscountVoucherPool()
            ->findPk($discountVoucherPoolTransfer->getIdDiscountVoucherPool())
        ;
        $discountVoucherPoolEntity->fromArray($discountVoucherPoolTransfer->toArray());
        $discountVoucherPoolEntity->save();

        return $discountVoucherPoolEntity;
    }

    /**
     * @param VoucherPoolTransfer $voucherPoolTransfer
     *
     * @return SpyDiscountVoucherPool
     */
    public function save(VoucherPoolTransfer $voucherPoolTransfer)
    {
        if ($voucherPoolTransfer->getIdDiscountVoucherPool() > 0) {
            return $this->update($voucherPoolTransfer);
        }

        return $this->create($voucherPoolTransfer);
    }

}
