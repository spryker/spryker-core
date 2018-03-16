<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer;
use Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface;

class OfferConverter implements OfferConverterInterface
{
    /**
     * @var \Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        OfferToSalesFacadeInterface $salesFacade
    ) {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idOffer
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertToOrder(int $idOffer): OfferToOrderConvertResponseTransfer
    {
        $offer = $this->salesFacade->getOrderByIdSalesOrder($idOffer);
        $isSuccess = false;
        if ($offer) {
            $offer->setIsOffer(false);
            $isSuccess = $this->salesFacade->updateOrder($offer, $offer->getIdSalesOrder());
        }

        return (new OfferToOrderConvertResponseTransfer())
            ->setOrder($offer)
            ->setIsSuccessful($isSuccess);
    }
}
