<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
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
    protected const MESSAGE_OFFER_RELOAD_SUCCESS = 'Offer was reloaded successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $offerTransfer = $this->getOfferTransfer($request);
        $offerTransfer = $this->processCustomerRedirect($request, $offerTransfer);

        $form = $this->getFactory()->getOfferForm($offerTransfer, $request);
        $form->handleRequest($request);

        if ($request->request->has(CreateController::PARAM_SUBMIT_CUSTOMER_CREATE)) {
            return $this->processCustomerCreateCall($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\OfferTransfer $offerTransfer */
            $offerTransfer = $form->getData();
            $offerTransfer = $this->getFactory()
                ->getOfferFacade()
                ->calculateOffer($offerTransfer);

            $form = $this->getFactory()->getOfferForm($offerTransfer, $request);
            $this->getFactory()->getFlashBag()->clear();

            $this->updateOffer($offerTransfer, $request);
        }

        return $this->viewResponse([
            'offer' => $offerTransfer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function updateOffer(OfferTransfer $offerTransfer, Request $request)
    {
        $isSubmitPersist = $request->request->get(static::PARAM_SUBMIT_PERSIST);

        if (!$isSubmitPersist) {
            $this->addSuccessMessage(static::MESSAGE_OFFER_RELOAD_SUCCESS);
            return;
        }

        $offerResponseTransfer = $this->getFactory()
            ->getOfferFacade()
            ->updateOffer($offerTransfer);

        if ($offerResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_OFFER_UPDATE_SUCCESS);
        }
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

        if ($request->isMethod('POST')) {
            $offerTransfer->getQuote()->setItems(new ArrayObject());
        }

        return $offerTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    protected function processCustomerRedirect(Request $request, OfferTransfer $offerTransfer): OfferTransfer
    {
        if (!$request->query->has(CreateController::PARAM_CUSTOMER_REFERENCE) || !$request->query->has(CreateController::PARAM_KEY_INITIAL_OFFER)) {
            return $offerTransfer;
        }
        $offerKey = $request->query->get(CreateController::PARAM_KEY_INITIAL_OFFER);

        return (new OfferTransfer())->fromArray(
            $this->retrieveFormDataFromSession($offerKey)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processCustomerCreateCall(Request $request)
    {
        $offerTransfer = $this->getOfferTransfer($request);
        $form = $this->getFactory()->getOfferForm($offerTransfer, $request);
        $form->handleRequest($request);

        $this->getFactory()
            ->createCreateRequestHandler()
            ->addItems($offerTransfer);

        $form = $this->getFactory()->getOfferForm($offerTransfer, $request);

        $redirectBackUrl = $this->storeFormDataIntoSession($form->getData());

        $redirectUrl = Url::generate(
            '/customer/add',
            [CreateController::PARAM_KEY_REDIRECT_URL => $redirectBackUrl]
        )->build();

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return string
     */
    protected function storeFormDataIntoSession(OfferTransfer $offerTransfer): string
    {
        $offerJsonData = $this->getFactory()->getUtilEncoding()->encodeJson($offerTransfer->toArray());
        $offerKey = $this->generateOfferKey($offerJsonData);

        $this->getFactory()
            ->getSessionClient()
            ->set($offerKey, $offerJsonData);

        $redirectUrl = Url::generate(
            '/offer-gui/edit',
            [
                static::PARAM_ID_OFFER => $offerTransfer->getIdOffer(),
                CreateController::PARAM_KEY_INITIAL_OFFER => $offerKey,
            ]
        )->build();

        return $redirectUrl;
    }

    /**
     * @param string $offerKey
     *
     * @return array
     */
    protected function retrieveFormDataFromSession(string $offerKey): array
    {
        $jsonData = $this->getFactory()
            ->getSessionClient()
            ->get($offerKey);

        return $this->getFactory()
            ->getUtilEncoding()
            ->decodeJson($jsonData, true);
    }

    /**
     * @param string $offerJsonData
     *
     * @return string
     */
    protected function generateOfferKey(string $offerJsonData): string
    {
        return md5($offerJsonData);
    }
}
