<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Persistence;

use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductOfferValidity\Persistence\ProductOfferValidityPersistenceFactory getFactory()
 */
class ProductOfferValidityEntityManager extends AbstractEntityManager implements ProductOfferValidityEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferValidityTransfer $productOfferValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function create(ProductOfferValidityTransfer $productOfferValidityTransfer): ProductOfferValidityTransfer
    {
        $productOfferValidityMapper = $this->getFactory()->createProductOfferValidityMapper();

        $productOfferValidityEntity = $productOfferValidityMapper->mapProductOfferValidityTransferToProductOfferValidityEntity(
            $productOfferValidityTransfer,
            new SpyProductOfferValidity()
        );

        $productOfferValidityEntity->save();

        return $productOfferValidityMapper->productOfferValidityEntityToProductOfferValidityTransfer(
            $productOfferValidityEntity,
            $productOfferValidityTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferValidityTransfer $productOfferValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function update(ProductOfferValidityTransfer $productOfferValidityTransfer): ProductOfferValidityTransfer
    {
        $productOfferValidityTransfer->requireIdProductOffer();

        $productOfferValidityEntity = $this->getFactory()
            ->createProductOfferValidityPropelQuery()
            ->findOneByFkProductOffer($productOfferValidityTransfer->getIdProductOffer());

        $productOfferValidityMapper = $this->getFactory()->createProductOfferValidityMapper();
        $productOfferValidityEntity = $productOfferValidityMapper->mapProductOfferValidityTransferToProductOfferValidityEntity(
            $productOfferValidityTransfer,
            $productOfferValidityEntity
        );
        $productOfferValidityEntity->save();

        return $productOfferValidityMapper->productOfferValidityEntityToProductOfferValidityTransfer(
            $productOfferValidityEntity,
            $productOfferValidityTransfer
        );
    }
}
