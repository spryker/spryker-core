<?php


namespace Spryker\Zed\Offer\Business\Model;


use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Orm\Zed\Offer\Persistence\SpyOffer;

class OfferWriter implements OfferWriterInterface
{
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        $offerTransfer->requireQuote();
        $offerTransfer->getQuote()->requireCustomer();

        //use repository
        //drop Customer object here and fill it from session on order creation

        $offer = new SpyOffer();
        $offer->setCustomerReference($offerTransfer->getQuote()->getCustomer()->getCustomerReference());
        $offer->setQuoteData(json_encode(
            $offerTransfer->getQuote()->modifiedToArray()
        ));
        $offer->setStatus('in_progress');
        $offer->save();

        $offerTransfer->setIdOffer($offer->getIdOffer());

        $offerTransfer->getQuote()->setCheckoutConfirmed(true);

        return (new OfferResponseTransfer())
            ->setIsSuccessful(true)
            ->setOffer($offerTransfer);
    }
}