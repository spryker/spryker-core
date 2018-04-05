<?php


namespace Spryker\Zed\OfferGui\Communication\Controller;


use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Cart\Business\CartFacadeInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\OfferGui\Communication\Form\Offer\CreateOfferType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|Response
     */
    public function indexAction(Request $request)
    {
        $offerTransfer = new OfferTransfer();

        /** @var \Spryker\Zed\Cart\Business\CartFacadeInterface $cartFacade */
        $cartFacade = Locator::getInstance()->cart()->facade();

        $form = $this->getFactory()->getOfferForm($offerTransfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\OfferTransfer $offerTransfer */
            $offerTransfer = $form->getData();
            $quoteTransfer = $offerTransfer->getQuote();

            //remove items
            $itemTransfers = new \ArrayObject();
            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getQuantity() > 0) {
                    $itemTransfers->append($itemTransfer);
                }
            }
            $quoteTransfer->setItems($itemTransfers);

            //add items
            $incomingItems = new \ArrayObject();
            foreach ($quoteTransfer->getIncomingItems() as $itemTransfer) {
                if ($itemTransfer->getSku()) {
                    $incomingItems->append($itemTransfer);
                }
            }

            foreach ($incomingItems as $itemTransfer) {
                $cartChangeTransfer = new CartChangeTransfer();
                $cartChangeTransfer->setQuote($quoteTransfer);
                $cartChangeTransfer->addItem($itemTransfer);

                $quoteTransfer = $cartFacade->add($cartChangeTransfer);
            }

            if ($quoteTransfer->getItems()->count() <= 0) {

                $this->addErrorMessage('Please fill offer with available items');

                return $this->viewResponse([
                    'offer' => $offerTransfer,
                    'form' => $form->createView(),
                ]);
            }

            //update cart
            $quoteTransfer = $cartFacade->reloadItems($quoteTransfer);
            $offerTransfer->setQuote($quoteTransfer);

            //refresh form after calculations
            $form = $this->getFactory()->getOfferForm($offerTransfer);
            //save offer and a quote

            $offerResponseTransfer = $this->getFactory()
                    ->getOfferFacade()
                    ->createOffer($offerTransfer);

            if ($offerResponseTransfer->getIsSuccessful()) {
                $redirectUrl = Url::generate(
                    '/offer-gui/edit',
                    [EditController::PARAM_ID_OFFER => $offerResponseTransfer->getOffer()->getIdOffer()]
                )->build();

                return $this->redirectResponse($redirectUrl);
            }
        }

        return $this->viewResponse([
            'offer' => $offerTransfer,
            'form' => $form->createView(),
        ]);
    }
}