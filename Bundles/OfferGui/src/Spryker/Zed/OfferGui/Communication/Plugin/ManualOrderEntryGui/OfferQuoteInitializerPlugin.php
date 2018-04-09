<?php


namespace Spryker\Zed\OfferGui\Communication\Plugin\ManualOrderEntryGui;


use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Plugin\QuoteInitializerPluginInterface;
use Spryker\Zed\Offer\Business\OfferFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package Spryker\Zed\OfferGui\Communication\Plugin\ManualOrderEntryGui
 */
class OfferQuoteInitializerPlugin implements QuoteInitializerPluginInterface
{
    public const PARAM_ID_OFFER = 'id-offer';

    public function initializeQuote(Request $request): ?QuoteTransfer
    {
        /** @var OfferFacadeInterface $offerFacade */
        $offerFacade = Locator::getInstance()->offer()->facade();

        $idOffer = $request->get(static::PARAM_ID_OFFER);

        if (!$idOffer) {
            return null;
        }

        $offerTransfer = new OfferTransfer();
        $offerTransfer->setIdOffer($idOffer);
        $offerTransfer = $offerFacade->getOfferById($offerTransfer);
        $quoteTransfer = $offerTransfer->getQuote();

        /** @var CustomerTransfer $customerTransfer */
        $customerTransfer = Locator::getInstance()->customer()->facade()->findByReference($offerTransfer->getCustomerReference());

        if ($customerTransfer) {
            $quoteTransfer->setCustomer($customerTransfer);
            $quoteTransfer->setIdCustomer($customerTransfer->getIdCustomer());
        }

        return $quoteTransfer;
    }

}