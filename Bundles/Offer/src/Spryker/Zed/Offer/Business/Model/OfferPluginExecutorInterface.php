<?php


namespace Spryker\Zed\Offer\Business\Model;


use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Offer\Persistence\OfferRepositoryInterface;

interface OfferPluginExecutorInterface
{

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferTransfer
     */
    public function hydrateOffer(OfferTransfer $offerTransfer): OfferTransfer;

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;
}