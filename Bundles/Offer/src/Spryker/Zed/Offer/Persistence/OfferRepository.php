<?php

namespace Spryker\Zed\Offer\Persistence;


use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyOfferEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Offer\Persistence\OfferPersistenceFactory getFactory()
 */
class OfferRepository extends AbstractRepository implements OfferRepositoryInterface
{
    /**
     * @param int $idOffer
     *
     * @return OfferTransfer
     */
    public function getOfferById(int $idOffer): OfferTransfer
    {
        $offerQuery = $this->getFactory()->createPropelOfferQuery();
        $offerQuery->filterByIdOffer($idOffer);

        $offerQuery = $this->buildQueryFromCriteria($offerQuery);
        $offerEntityTransfer = $offerQuery->findOne();


        $offerTransfer = $this->mapOffer((new OfferTransfer()), $offerEntityTransfer);
        $offerTransfer = $this->decodeQuote($offerTransfer, $offerEntityTransfer);

        return $offerTransfer;
    }

    /**
     * @param OfferTransfer $offerTransfer
     * @param SpyOfferEntityTransfer $offerEntityTransfer
     *
     * @return OfferTransfer
     */
    protected function mapOffer(OfferTransfer $offerTransfer, SpyOfferEntityTransfer $offerEntityTransfer)
    {
        return $offerTransfer->fromArray($offerEntityTransfer->toArray(), true);
    }


    /**
     * todo: to a mapper
     * @param OfferTransfer $offerTransfer
     * @param SpyOfferEntityTransfer $offerEntityTransfer
     *
     * @return OfferTransfer
     */
    protected function decodeQuote(OfferTransfer $offerTransfer, SpyOfferEntityTransfer $offerEntityTransfer)
    {
        $offerTransfer->setQuote(
            (new QuoteTransfer())
            ->fromArray(json_decode($offerEntityTransfer->getQuoteData(), true))
        );

        return $offerTransfer;
    }
}