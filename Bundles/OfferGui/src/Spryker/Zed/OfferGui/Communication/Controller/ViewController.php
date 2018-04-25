<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    public const PARAM_ID_OFFER = 'id-offer';

    /**
     * @return array
     */
    public function listAction()
    {
        return $this->viewResponse([
            'offers' => $this->getFactory()
                ->createOffersTable()
                ->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createOffersTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailsAction(Request $request)
    {
        $idOffer = $this->castId($request->query->getInt(static::PARAM_ID_OFFER));
        $offerTransfer = new OfferTransfer();
        $offerTransfer->setIdOffer($idOffer);

        $offerTransfer = $this->getFactory()
            ->getOfferFacade()
            ->getOfferById($offerTransfer);

        return $this->viewResponse([
            'offer' => $offerTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function printVersionAction(Request $request)
    {
        $idOffer = $this->castId($request->query->getInt(static::PARAM_ID_OFFER));
        $offerTransfer = new OfferTransfer();
        $offerTransfer->setIdOffer($idOffer);

        $offerTransfer = $this->getFactory()
            ->getOfferFacade()
            ->getOfferById($offerTransfer);

        return $this->viewResponse([
            'offer' => $offerTransfer,
        ]);
    }
}
