<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;

class DiscountWriter extends AbstractWriter
{

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpyDiscount
     */
    public function create(DiscountTransfer $discountTransfer)
    {
        $discountEntity = new SpyDiscount();
        $discountEntity->fromArray($discountTransfer->toArray());
        $discountEntity->save();

        return $discountEntity;
    }

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @throws PropelException
     *
     * @return SpyDiscount
     */
    public function update(DiscountTransfer $discountTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountEntity = $queryContainer->queryDiscount()->findPk($discountTransfer->getIdDiscount());
        $discountEntity->fromArray($discountTransfer->toArray());
        $discountEntity->save();

        return $discountEntity;
    }

    /**
     * @param int $idDiscount
     *
     * @return DiscountTransfer
     */
    public function toggleActiveStatus($idDiscount)
    {
        $queryContainer = $this->getQueryContainer();
        $discountEntity = $queryContainer->queryDiscount()->findPk($idDiscount);
        if (!$discountEntity->isActive()) {
            $discountEntity->setIsActive(true);
        } else {
            $discountEntity->setIsActive(false);
        }
        $discountEntity->save();

        return (new DiscountTransfer())->fromArray($discountEntity->toArray(), true);
    }
}
