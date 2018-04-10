<?php


namespace Spryker\Zed\OfferGui\Communication\Plugin\ManualOrderEntryGui;


use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Plugin\QuoteInitializerPluginInterface;
use Spryker\Zed\Offer\Business\OfferFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class OfferQuoteInitializerPlugin extends AbstractPlugin implements QuoteInitializerPluginInterface
{
    public const PARAM_ID_OFFER = 'id-offer';

    public function initializeQuote(Request $request): ?QuoteTransfer
    {
        $idOffer = $request->get(static::PARAM_ID_OFFER);

        if (!$idOffer) {
            return null;
        }

        $offerTransfer = (new OfferTransfer())
            ->setIdOffer($idOffer);

        $offerTransfer = $this->getFactory()
            ->getOfferFacade()
            ->getOfferById($offerTransfer);

        $quoteTransfer = $offerTransfer->getQuote();

        $customerTransfer = $this->getFactory()
            ->getCustomerFacade()
            ->findCustomerByReference(
                $offerTransfer->getCustomerReference()
            );

        if ($customerTransfer) {
            $quoteTransfer->setCustomer($customerTransfer);
            $quoteTransfer->setIdCustomer($customerTransfer->getIdCustomer());
        }

        return $quoteTransfer;
    }

}