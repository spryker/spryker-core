<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class CopyController extends AbstractController
{
    public const PARAM_ID_OFFER = 'id-offer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $idOffer = $request->get(static::PARAM_ID_OFFER);

        $offerTransfer = new OfferTransfer();
        $offerTransfer->setIdOffer($idOffer);
        $offerTransfer = $this->getFactory()
            ->getOfferFacade()
            ->getOfferById($offerTransfer);

        $offerTransfer->setIdOffer(null);
        $offerTransfer->setCustomerReference(null);
        $offerTransfer->setCustomer(new CustomerTransfer());

        $offerJson = \json_encode($offerTransfer->toArray());
        $offerKey = md5($offerJson);

        $this->getFactory()->getSessionClient()->set($offerKey, $offerJson);

        $redirectUrl = Url::generate(
            '/offer-gui/create',
            [CreateController::PARAM_KEY_INITIAL_OFFER => $offerKey]
        )->build();

        return $this->redirectResponse($redirectUrl);
    }
}
