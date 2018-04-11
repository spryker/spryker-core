<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Offer\Business\OfferBusinessFactory getFactory()
 * @method \Spryker\Zed\Offer\Persistence\OfferRepositoryInterface getRepository()
 */
class OfferFacade extends AbstractFacade implements OfferFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOffers(OfferListTransfer $offerListTransfer): OfferListTransfer
    {
        return $this->getFactory()
            ->createOfferReader()
            ->getOfferList($offerListTransfer);
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
        return $this->getFactory()
            ->createOfferReader()
            ->getOfferById($offerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function createOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        return $this->getFactory()
            ->createOfferWriter()
            ->createOffer($offerTransfer);
    }

    /**
     * {@inheritdoc}
     *
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
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        return $this->getFactory()
            ->createOfferWriter()
            ->updateOffer($offerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function aggregateOfferItemSubtotal(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()
            ->createOfferItemSubtotalAggregator()
            ->recalculate($calculableObjectTransfer);
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
    public function hydrateOfferWithSavingAmount(OfferTransfer $offerTransfer): OfferTransfer
    {
        return $this->getFactory()
            ->createOfferSavingAmountHydrator()
            ->hydrate($offerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateGrandTotal(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()
            ->createOfferGrandTotalCalculator()
            ->recalculateGrandTotal($calculableObjectTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idOffer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteUsingOffer(QuoteTransfer $quoteTransfer, int $idOffer): QuoteTransfer
    {
        return $this->getFactory()->createOfferQuoteExpander()->expand($quoteTransfer, $idOffer);
    }
}
