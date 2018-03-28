<?php


namespace Spryker\Zed\Offer\Business\Model;


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
}