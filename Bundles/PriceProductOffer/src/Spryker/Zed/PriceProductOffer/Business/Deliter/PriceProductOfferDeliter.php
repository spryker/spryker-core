<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Deliter;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface;

class PriceProductOfferDeliter implements PriceProductOfferDeliterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface
     */
    protected $priceProductOfferEntityManager;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface $priceProductOfferEntityManager
     */
    public function __construct(
        PriceProductOfferToPriceProductFacadeInterface $priceProductFacade,
        PriceProductOfferEntityManagerInterface $priceProductOfferEntityManager
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceProductOfferEntityManager = $priceProductOfferEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return void
     */
    public function deleteProductOfferPrices(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer): void
    {
        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferIds = [];
        /** @var \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer */
        foreach ($priceProductOfferCollectionTransfer->getPriceProductOffers() as $priceProductOfferTransfer) {
            $priceProductOfferIds[] = $priceProductOfferTransfer->getIdPriceProductOfferOrFail();
        }
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($priceProductOfferIds);
        $this->priceProductOfferEntityManager->delete($priceProductOfferCriteriaTransfer);
        $this->priceProductFacade->deleteOrphanPriceProductStoreEntities();
    }
}
