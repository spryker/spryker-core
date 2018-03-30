<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Offer\Business\OfferBusinessFactory getFactory()
 * @method \Spryker\Zed\Offer\Persistence\OfferRepositoryInterface getRepository()
 */
class OfferFacade extends AbstractFacade implements OfferFacadeInterface
{
    /**
     * {@inheritdoc}
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOffers(OfferListTransfer $offerListTransfer): OfferListTransfer
    {
        return $this->getRepository()
            ->getOffers($offerListTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        return $this->getFactory()
            ->createOfferWriter()
            ->placeOffer($offerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getOfferById(OfferTransfer $offerTransfer): OfferTransfer
    {
        return $this->getRepository()->getOfferById($offerTransfer->getIdOffer());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idOffer
     * @param string $status
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function updateOfferStatus(int $idOffer, string $status): OfferResponseTransfer
    {
        return $this->getFactory()
            ->createOfferWriter()
            ->updateOfferStatus($idOffer, $status);
    }
}
