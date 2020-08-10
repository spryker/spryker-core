<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferPersistenceFactory getFactory()
 */
class PriceProductOfferEntityManager extends AbstractEntityManager implements PriceProductOfferEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductOfferRelation(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductOfferMapper = $this->getFactory()->createPriceProductOfferMapper();

        $priceProductOfferEntity = $priceProductOfferMapper->mapPriceProductTransferToPriceProductOfferEntity(
            $priceProductTransfer,
            new SpyPriceProductOffer()
        );

        $priceProductOfferEntity->save();

        return $priceProductOfferMapper->mapPriceProductOfferEntityToPriceProductTransfer(
            $priceProductOfferEntity,
            $priceProductTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function updatePriceProductOfferRelation(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $idPriceProductOffer = $priceProductTransfer->requirePriceDimension()
            ->getPriceDimension()
            ->requireIdProductOffer()
            ->getIdPriceProductOffer();

        $priceProductOfferEntity = $this->getFactory()
            ->getPriceProductOfferPropelQuery()
            ->filterByIdPriceProductOffer($idPriceProductOffer)
            ->findOne();

        if (!$priceProductOfferEntity) {
            return $priceProductTransfer;
        }

        $priceProductOfferMapper = $this->getFactory()->createPriceProductOfferMapper();

        $priceProductOfferEntity = $priceProductOfferMapper->mapPriceProductTransferToPriceProductOfferEntity(
            $priceProductTransfer,
            $priceProductOfferEntity
        );
        $priceProductOfferEntity->save();

        return $priceProductOfferMapper->mapPriceProductOfferEntityToPriceProductTransfer(
            $priceProductOfferEntity,
            $priceProductTransfer
        );
    }
}
