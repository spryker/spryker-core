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
use Spryker\Zed\OfferGui\Communication\Form\Product\ProductCollectionType;
use Spryker\Zed\OfferGui\Communication\Form\Voucher\OfferType;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    public const PARAM_ID_OFFER = 'id-offer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idOffer = $request->get(static::PARAM_ID_OFFER);

        $offerTransfer = new OfferTransfer();
        $offerTransfer->setIdOffer($idOffer);

        $offerTransfer = $this->getFactory()->getOfferFacade()->getOfferById($offerTransfer);

        $offerTransfer->getQuote()->addItem((new ItemTransfer()));
        $offerTransfer->getQuote()->addItem((new ItemTransfer()));
        $offerTransfer->getQuote()->addItem((new ItemTransfer()));

        $form = $this->getFactory()->getOfferForm($offerTransfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferTransfer $offerTransfer */
            $offerTransfer = $form->getData();

            $items = new \ArrayObject();
            foreach ($offerTransfer->getQuote()->getItems() as $itemTransfer) {
                if ($itemTransfer->getSku()) {
                    $items->append($itemTransfer);
                }
            }

            $offerTransfer->getQuote()->setItems($items);

            $cartChangeTransfer = new CartChangeTransfer();
            $cartChangeTransfer->setQuote($offerTransfer->getQuote());

            foreach($offerTransfer->getQuote()->getItems() as $itemTransfer) {
                if (!$itemTransfer->getSku()) {
                    continue;
                }

                $cartChangeTransfer->addItem($itemTransfer);

                /** @var CartFacadeInterface $cartFacade */
                $cartFacade = Locator::getInstance()->cart()->facade();
                $quoteTransfer = $cartFacade->add($cartChangeTransfer);
            }

            //save quote

            dump($offerTransfer);
            exit;
        }

        return $this->viewResponse([
            'offer' => $offerTransfer,
            'form' => $form->createView()
        ]);
    }
}
