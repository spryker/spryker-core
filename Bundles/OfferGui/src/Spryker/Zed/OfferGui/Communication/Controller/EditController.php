<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Kernel\Locator;
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

        $form = $this->createOfferForm($offerTransfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\OfferTransfer $offerTransfer */
            $offerTransfer = $form->getData();
            $quoteTransfer = $offerTransfer->getQuote();

            //prepare incoming items to be added
            $incomingItems = new ArrayObject();
            foreach ($quoteTransfer->getIncomingItems() as $itemTransfer) {
                if ($itemTransfer->getSku()) {
                    $incomingItems->append($itemTransfer);
                }
            }

            $cartChangeTransfer = new CartChangeTransfer();
            $cartChangeTransfer->setQuote($quoteTransfer);
            foreach ($incomingItems as $itemTransfer) {
                $cartChangeTransfer->addItem($itemTransfer);

                /** @var \Spryker\Zed\Cart\Business\CartFacadeInterface $cartFacade */
                $cartFacade = Locator::getInstance()->cart()->facade();
                $quoteTransfer = $cartFacade->add($cartChangeTransfer);
            }

            $offerTransfer->setQuote($quoteTransfer);

            $form = $this->createOfferForm($offerTransfer);
            //save offer and a quote

//            dump($offerTransfer);
//            exit;
        }

        return $this->viewResponse([
            'offer' => $offerTransfer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createOfferForm(OfferTransfer $offerTransfer)
    {
        $offerTransfer
            ->getQuote()
            ->setIncomingItems(new ArrayObject([
                new ItemTransfer(),
                new ItemTransfer(),
                new ItemTransfer(),
            ]));

        return $this->getFactory()->getOfferForm($offerTransfer);
    }
}
