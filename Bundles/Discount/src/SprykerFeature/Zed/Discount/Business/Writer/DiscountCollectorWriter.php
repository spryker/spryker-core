<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountCollector;

class DiscountCollectorWriter extends AbstractWriter
{

    /**
     * @param DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return SpyDiscountCollector
     */
    public function create(DiscountCollectorTransfer $discountCollectorTransfer)
    {
        $discountCollectorEntity = new SpyDiscountCollector();
        $discountCollectorEntity->fromArray($discountCollectorTransfer->toArray());
        $discountCollectorEntity->save();

        return $discountCollectorEntity;
    }

    /**
     * @param DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return SpyDiscountCollector
     */
    public function save(DiscountCollectorTransfer $discountCollectorTransfer)
    {
        if ($discountCollectorTransfer->getIdDiscountCollector() === null) {
            return $this->create($discountCollectorTransfer);
        }

        return $this->update($discountCollectorTransfer);
    }

    /**
     * @param DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return SpyDiscountCollector
     */
    public function update(DiscountCollectorTransfer $discountCollectorTransfer)
    {
        $discountCollectorEntity = $this->getQueryContainer()
            ->queryDiscountCollectorById($discountCollectorTransfer->getIdDiscountCollector())
            ->findOne();

        $discountCollectorEntity->fromArray($discountCollectorTransfer->toArray());
        $discountCollectorEntity->save();

        return $discountCollectorEntity;
    }

}
