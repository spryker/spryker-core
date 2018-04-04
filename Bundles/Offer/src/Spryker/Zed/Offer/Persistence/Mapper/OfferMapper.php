<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Persistence\Mapper;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyOfferEntityTransfer;

class OfferMapper implements OfferMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyOfferEntityTransfer $offerEntityTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function mapOfferEntityToOffer(SpyOfferEntityTransfer $offerEntityTransfer): OfferTransfer
    {
        $offerTransfer = (new OfferTransfer())->fromArray($offerEntityTransfer->toArray(), true);
        $offerTransfer = $this->decodeQuote($offerTransfer, $offerEntityTransfer);

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\SpyOfferEntityTransfer
     */
    public function mapOfferToOfferEntity(OfferTransfer $offerTransfer): SpyOfferEntityTransfer
    {
        //todo: remove customer from quote (save only selected fields)
        //todo: replace json_encode with util
        $offerEntityTransfer = (new SpyOfferEntityTransfer())->fromArray($offerTransfer->toArray(), true);
        $offerEntityTransfer->setQuoteData(json_encode(
            $offerTransfer->getQuote()->toArray()
        ));

        return $offerEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     * @param \Generated\Shared\Transfer\SpyOfferEntityTransfer $offerEntityTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    protected function decodeQuote(OfferTransfer $offerTransfer, SpyOfferEntityTransfer $offerEntityTransfer)
    {
        $offerTransfer->setQuote(
            (new QuoteTransfer())
                ->fromArray(
                    json_decode($offerEntityTransfer->getQuoteData(), true),
                    true
                )
        );

        //TODO: suggest a better way to understand in post order save plugin, whether we shuld disable an offer.
        $offerTransfer->getQuote()->setIdOffer($offerTransfer->getIdOffer());

        return $offerTransfer;
    }
}
