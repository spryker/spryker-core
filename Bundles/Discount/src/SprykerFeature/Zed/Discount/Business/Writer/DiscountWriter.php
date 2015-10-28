<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountTransfer;
use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Discount\Persistence\SpyDiscount;

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
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpyDiscount
     */
    public function save(DiscountTransfer $discountTransfer)
    {
        if ($discountTransfer->getIdDiscount() > 0) {
            return $this->update($discountTransfer);
        }

        return $this->create($discountTransfer);
    }

}
