<?php


namespace Spryker\Zed\Offer\Business\Model;


use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Orm\Zed\Offer\Persistence\SpyOffer;
use Orm\Zed\Offer\Persistence\SpyOfferQuery;

class OfferWriter implements OfferWriterInterface
{
    /**
     * @var OfferPluginExecutorInterface
     */
    protected $offerPluginExecutor;

    public function __construct(
        OfferPluginExecutorInterface $offerPluginExecutor
    ) {
        $this->offerPluginExecutor = $offerPluginExecutor;
    }

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

    /**
     * @throws \Exception
     *
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        $offerTransfer->requireIdOffer();

        /** @var SpyOffer $offerEntity */
        $offerEntity = SpyOfferQuery::create()
            ->filterByIdOffer($offerTransfer->getIdOffer())
            ->findOne();

        if (!$offerEntity) {
            throw new \Exception();
        }

        $offerEntity->fromArray($offerTransfer->toArray());

        $offerEntity->setQuoteData(
            json_encode($offerTransfer->getQuote()->toArray())
        );

        $offerEntity->save();

        $offerResponseTransfer = $this->offerPluginExecutor->updateOffer($offerTransfer);

        return $offerResponseTransfer;
    }
}