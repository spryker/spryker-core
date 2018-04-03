<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Cart\Business\CartFacadeInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Kernel\Locator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    public const PARAM_ID_OFFER = 'id-offer';
    public const PARAM_SUBMIT_RELOAD = 'submit-reload';
    public const PARAM_SUBMIT_PERSIST = 'submit-persist';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idOffer = $request->get(static::PARAM_ID_OFFER);
        $isSubmitPersist = $request->request->get(static::PARAM_SUBMIT_PERSIST);

        $offerTransfer = new OfferTransfer();
        $offerTransfer->setIdOffer($idOffer);

        $offerTransfer = $this->getFactory()
            ->getOfferFacade()
            ->getOfferById($offerTransfer);

        /** @var CartFacadeInterface $cartFacade */
        $cartFacade = Locator::getInstance()->cart()->facade();

        $form = $this->createOfferForm($offerTransfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferTransfer $offerTransfer */
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
            $itemTransfers = new \ArrayObject();
            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getQuantity() > 0) {
                    $itemTransfers->append($itemTransfer);
                }
            }
            $quoteTransfer->setItems($itemTransfers);

            //add items
            $incomingItems = new \ArrayObject;
            foreach($quoteTransfer->getIncomingItems() as $itemTransfer) {
                if ($itemTransfer->getSku()) {
                    $incomingItems->append($itemTransfer);
                }
            }

            foreach($incomingItems as $itemTransfer) {
                $cartChangeTransfer = new CartChangeTransfer();
                $cartChangeTransfer->setQuote($quoteTransfer);
                $cartChangeTransfer->addItem($itemTransfer);

                $quoteTransfer = $cartFacade->add($cartChangeTransfer);
            }

            //update cart
            $quoteTransfer = $cartFacade->reloadItems($quoteTransfer);
            $offerTransfer->setQuote($quoteTransfer);

            //refresh form after calculations
            $form = $this->createOfferForm($offerTransfer);
            //save offer and a quote

            if ($isSubmitPersist) {
                $this->getFactory()
                    ->getOfferFacade()
                    ->updateOffer($offerTransfer);
            }
        }

        return $this->viewResponse([
            'offer' => $offerTransfer,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createOfferForm(OfferTransfer $offerTransfer)
    {
        $offerTransfer
            ->getQuote()
            ->setIncomingItems(new \ArrayObject([
                new ItemTransfer(),
                new ItemTransfer(),
                new ItemTransfer()
            ]));

        return $this->getFactory()->getOfferForm($offerTransfer);
    }
}
