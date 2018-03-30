<?php

namespace Spryker\Zed\Offer\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Offer\OfferConfig getConfig()
 * @method \Spryker\Zed\Offer\Business\OfferFacadeInterface getFacade()
 * @method \Spryker\Zed\Offer\Communication\OfferCommunicationFactory getFactory()
 */
class ConvertOfferToOrderPlugin extends AbstractPlugin implements CheckoutPostSaveHookInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executeHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $idOffer = $quoteTransfer->getIdOffer();

        $this->getFacade()->updateOfferStatus(
            $idOffer,
            $this->getConfig()->getStatusOrder()
        );
    }
}
