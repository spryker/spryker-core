<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    public const PARAM_ID_OFFER = 'id-offer';
    public const PARAM_SUBMIT_RELOAD = 'submit-reload';
    public const PARAM_SUBMIT_PERSIST = 'submit-persist';
    protected const MESSAGE_OFFER_UPDATE_SUCCESS = 'Offer was updated successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $isSubmitPersist = $request->request->get(static::PARAM_SUBMIT_PERSIST);

        $offerTransfer = $this->getOfferTransfer($request);

        $form = $this->getFactory()->getOfferForm($offerTransfer, $request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\OfferTransfer $offerTransfer */
            $offerTransfer = $form->getData();
            $quoteTransfer = $offerTransfer->getQuote();

            //remove vouchers
            $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();
            foreach ($quoteTransfer->getVoucherDiscounts() as $key => $discountTransfer) {
                if (!$discountTransfer->getVoucherCode()) {
                    $voucherDiscounts->offsetUnset($key);
                }
            }
            $quoteTransfer->setVoucherDiscounts($voucherDiscounts);

            //remove items
            $itemTransfers = new ArrayObject();
            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getQuantity() > 0) {
                    $itemTransfers->append($itemTransfer);
                }
            }
            $quoteTransfer->setItems($itemTransfers);

            //add items
            $incomingItems = new ArrayObject;
            foreach ($quoteTransfer->getIncomingItems() as $itemTransfer) {
                if ($itemTransfer->getSku()) {
                    $incomingItems->append($itemTransfer);
                }
            }

            foreach ($incomingItems as $itemTransfer) {
                $cartChangeTransfer = new CartChangeTransfer();
                $cartChangeTransfer->setQuote($quoteTransfer);
                $cartChangeTransfer->addItem($itemTransfer);

                $quoteTransfer = $this->getFactory()
                    ->getCartFacade()
                    ->add($cartChangeTransfer);
            }

            //update cart
            $quoteTransfer = $this->getFactory()
                ->getCartFacade()
                ->reloadItems($quoteTransfer);

            $offerTransfer->setQuote($quoteTransfer);

            //refresh form after calculations
            $form = $this->getFactory()->getOfferForm($offerTransfer, $request);
            //save offer and a quote

            if ($isSubmitPersist) {
                $this->getFactory()
                    ->getOfferFacade()
                    ->updateOffer($offerTransfer);
            }

            $this->addSuccessMessage(static::MESSAGE_OFFER_UPDATE_SUCCESS);
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
        $idOffer = $request->get(static::PARAM_ID_OFFER);

        $offerTransfer = new OfferTransfer();

        $offerTransfer->setIdOffer($idOffer);
        $offerTransfer = $this->getFactory()
            ->getOfferFacade()
            ->getOfferById($offerTransfer);

        return $offerTransfer;
    }
}
