<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountDiscountVoucherPoolTransfer;
use SprykerFeature\Zed\Discount\Business\Writer\AbstractWriter;

/**
 * Class DiscountVoucherPoolManager
 * @package SprykerFeature\Zed\Discount\Business\Model
 */
class DiscountVoucherPoolWriter extends AbstractWriter
{
    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param DiscountVoucherPool $discountVoucherPoolTransfer
     * @return mixed
     */
    public function create(DiscountVoucherPool $discountVoucherPoolTransfer)
    {
        $discountVoucherPoolEntity = $this->locator->discount()->entitySpyDiscountVoucherPool();
        $discountVoucherPoolEntity->fromArray($discountVoucherPoolTransfer->toArray());
        $discountVoucherPoolEntity->save();

        return $discountVoucherPoolEntity;
    }

    /**
     * @param DiscountVoucherPool $discountVoucherPoolTransfer
     * @return array|mixed|\SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function update(DiscountVoucherPool $discountVoucherPoolTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountVoucherPoolEntity = $queryContainer->queryDiscountVoucherPool()
            ->findPk($discountVoucherPoolTransfer->getIdDiscountVoucherPool());
        $discountVoucherPoolEntity->fromArray($discountVoucherPoolTransfer->toArray());
        $discountVoucherPoolEntity->save();

        return $discountVoucherPoolEntity;
    }
}
