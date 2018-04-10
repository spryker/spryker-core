<?php

namespace Spryker\Zed\Availability\Communication\Plugin\Offer;


use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\Availability\Communication\AvailabilityCommunicationFactory getFactory()
 */
class OfferQuoteItemStockHydratorPlugin extends AbstractPlugin implements OfferHydratorPluginInterface
{
    /**
     * //todo: move BL behind facade
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferTransfer
     */
    public function hydrateOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        return $this->getFacade()->hydrateOfferWithQuoteItemStock($offerTransfer);
    }
}