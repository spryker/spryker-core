<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    public const PARAM_KEY_INITIAL_OFFER = 'key-offer';
    public const PARAM_SUBMIT_PERSIST = 'submit-persist';

    protected const ERROR_MESSAGE_ITEMS_NOT_AVAILABLE = 'Please fill offer with available items';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $isSubmitPersist = $request->request->get(static::PARAM_SUBMIT_PERSIST);
        $offerTransfer = $this->getOfferTransfer($request);

        $form = $this->getFactory()->getOfferForm($offerTransfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\OfferTransfer $offerTransfer */
            $offerTransfer = $form->getData();
            $quoteTransfer = $offerTransfer->getQuote();

            //remove items
            $itemTransfers = new ArrayObject();
            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getQuantity() > 0) {
                    $itemTransfers->append($itemTransfer);
                }
            }
            $quoteTransfer->setItems($itemTransfers);

            //add items
            $incomingItems = new ArrayObject();
            foreach ($quoteTransfer->getIncomingItems() as $itemTransfer) {
                if ($itemTransfer->getSku()) {
                    $incomingItems->append($itemTransfer);
                }
            }

            foreach ($incomingItems as $itemTransfer) {
                $cartChangeTransfer = (new CartChangeTransfer())
                    ->setQuote($quoteTransfer)
                    ->addItem($itemTransfer);

                $quoteTransfer = $this->getFactory()
                    ->getCartFacade()
                    ->add($cartChangeTransfer);
            }

            if ($quoteTransfer->getItems()->count() <= 0) {
                $this->addErrorMessage(static::ERROR_MESSAGE_ITEMS_NOT_AVAILABLE);

                return $this->viewResponse([
                    'offer' => $offerTransfer,
                    'form' => $form->createView(),
                ]);
            }

            //update cart
            $quoteTransfer = $this->getFactory()
                ->getCartFacade()
                ->reloadItems($quoteTransfer);
            $offerTransfer->setQuote($quoteTransfer);

            //refresh form after calculations
            $form = $this->getFactory()->getOfferForm($offerTransfer);
            //save offer and a quote

            if ($isSubmitPersist) {
                $offerResponseTransfer = $this->getFactory()
                    ->getOfferFacade()
                    ->createOffer($offerTransfer);

                if ($offerResponseTransfer->getIsSuccessful()) {
                    return $this->getSuccessfulRedirect($offerResponseTransfer);
                }
            }
        }

        return $this->viewResponse([
            'offer' => $offerTransfer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    protected function getOfferTransfer(Request $request)
    {
        $keyOffer = $request->get(static::PARAM_KEY_INITIAL_OFFER);

        $offerJson = $this->getFactory()
            ->getSessionClient()
            ->get($keyOffer);

        $offerTransfer = new OfferTransfer();

        if ($offerJson !== null) {
            $offerTransfer->fromArray(\json_decode($offerJson, true));
        }

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferResponseTransfer $offerResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getSuccessfulRedirect(OfferResponseTransfer $offerResponseTransfer)
    {
        $this->getFactory()->getMessengerFacade()->getStoredMessages();

        $redirectUrl = Url::generate(
            '/offer-gui/edit',
            [EditController::PARAM_ID_OFFER => $offerResponseTransfer->getOffer()->getIdOffer()]
        )->build();

        return $this->redirectResponse($redirectUrl);
    }
}
