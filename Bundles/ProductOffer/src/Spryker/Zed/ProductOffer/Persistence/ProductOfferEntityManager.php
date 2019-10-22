<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferPersistenceFactory getFactory()
 */
class ProductOfferEntityManager extends AbstractEntityManager implements ProductOfferEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function saveProductOffer(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferEntity = $this->getFactory()
            ->createProductOfferQuery()
            ->filterByIdProductOffer($productOfferTransfer->getIdProductOffer())
            ->findOneOrCreate();

        $productOfferEntity->fromArray($productOfferTransfer->toArray());
        $productOfferEntity->save();

        return $this->getFactory()
            ->createPropelProductOfferMapper()
            ->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, $productOfferTransfer);
    }
}
