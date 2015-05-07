<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountDiscountVoucherPoolTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Discount\Business\Writer\AbstractWriter;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;

class DiscountVoucherPoolWriter extends AbstractWriter
{
    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer
     * @return mixed
     */
    public function create(DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        $discountVoucherPoolEntity = $this->locator->discount()->entitySpyDiscountVoucherPool();
        $discountVoucherPoolEntity->fromArray($discountVoucherPoolTransfer->toArray());
        $discountVoucherPoolEntity->save();

        return $discountVoucherPoolEntity;
    }

    /**
     * @param DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer
     * @return array|mixed|SpyDiscountVoucherPool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function update(DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountVoucherPoolEntity = $queryContainer->queryDiscountVoucherPool()
            ->findPk($discountVoucherPoolTransfer->getIdDiscountVoucherPool());
        $discountVoucherPoolEntity->fromArray($discountVoucherPoolTransfer->toArray());
        $discountVoucherPoolEntity->save();

        return $discountVoucherPoolEntity;
    }
}
