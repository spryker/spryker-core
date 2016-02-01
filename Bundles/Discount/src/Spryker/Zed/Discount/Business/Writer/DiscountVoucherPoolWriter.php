<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\VoucherPoolTransfer;
use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;

class DiscountVoucherPoolWriter extends AbstractWriter
{

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    public function create(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        $discountVoucherPoolEntity = new SpyDiscountVoucherPool();
        $discountVoucherPoolEntity->fromArray($discountVoucherPoolTransfer->toArray());
        $discountVoucherPoolEntity->save();

        return $discountVoucherPoolEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    public function update(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountVoucherPoolEntity = $queryContainer->queryDiscountVoucherPool()
            ->findPk($discountVoucherPoolTransfer->getIdDiscountVoucherPool());
        $discountVoucherPoolEntity->fromArray($discountVoucherPoolTransfer->toArray());
        $discountVoucherPoolEntity->save();

        return $discountVoucherPoolEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $voucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    public function save(VoucherPoolTransfer $voucherPoolTransfer)
    {
        if ($voucherPoolTransfer->getIdDiscountVoucherPool() > 0) {
            return $this->update($voucherPoolTransfer);
        }

        return $this->create($voucherPoolTransfer);
    }

}
