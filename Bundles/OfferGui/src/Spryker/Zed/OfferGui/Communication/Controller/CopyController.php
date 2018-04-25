<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class CopyController extends AbstractController
{
    public const PARAM_ID_OFFER = 'id-offer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $idOffer = $request->get(static::PARAM_ID_OFFER);

        if (!$idOffer) {
            throw new NotFoundHttpException();
        }

        $offerTransfer = $this->getOffer($idOffer);
        $offerKey = $this->persistOfferToSession($offerTransfer);

        $redirectUrl = Url::generate(
            '/offer-gui/create',
            [CreateController::PARAM_KEY_INITIAL_OFFER => $offerKey]
        )->build();

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param int $idOffer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    protected function getOffer(int $idOffer)
    {
        $offerTransfer = (new OfferTransfer())->setIdOffer($idOffer);
        $offerTransfer = $this->getFactory()
            ->getOfferFacade()
            ->getOfferById($offerTransfer);

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return string
     */
    protected function persistOfferToSession(OfferTransfer $offerTransfer)
    {
        $offerTransfer = $this->cleanupOfferForSession($offerTransfer);

        $offerJson = $this->getFactory()
            ->getUtilEncoding()
            ->encodeJson($offerTransfer->toArray());

        $offerKey = md5($offerJson);

        $this->getFactory()
            ->getSessionClient()
            ->set($offerKey, $offerJson);

        return $offerKey;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    protected function cleanupOfferForSession(OfferTransfer $offerTransfer)
    {
        $offerTransfer->setIdOffer(null)
            ->setCustomerReference(null)
            ->setCustomer(new CustomerTransfer());

        if ($offerTransfer->getQuote()) {
            $offerTransfer->getQuote()->setBillingAddress(new AddressTransfer());
            $offerTransfer->getQuote()->setShippingAddress(new AddressTransfer());
        }

        return $offerTransfer;
    }
}
