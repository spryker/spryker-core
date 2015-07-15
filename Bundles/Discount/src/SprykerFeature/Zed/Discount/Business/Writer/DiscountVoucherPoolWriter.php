<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;

class DiscountVoucherPoolWriter extends AbstractWriter
{

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return mixed
     */
    public function create(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        $discountVoucherPoolEntity = $this->locator->discount()->entitySpyDiscountVoucherPool();
        $discountVoucherPoolEntity->fromArray($discountVoucherPoolTransfer->toArray());
        $discountVoucherPoolEntity->save();

        return $discountVoucherPoolEntity;
    }

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return array|mixed|SpyDiscountVoucherPool
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

}
