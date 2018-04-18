<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    public const PARAM_KEY_INITIAL_OFFER = 'key-offer';
    public const PARAM_SUBMIT_PERSIST = 'submit-persist';
    public const PARAM_SUBMIT_CUSTOMER_CREATE = 'submit-customer-create';
    public const PARAM_SUBMIT_RELOAD = 'submit-reload';
    public const PARAM_CUSTOMER_REFERENCE = 'customerReference';
    public const REDIRECT_URL_OFFER_VIEW = '/offer-gui/view/details';

    protected const SESSION_KEY_OFFER_DATA = 'key-offer-data';

    protected const ERROR_MESSAGE_ITEMS_NOT_AVAILABLE = 'Please fill offer with available items';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        //TODO: think about getting rid of CreateOfferHandler
        //TODO: reduce code duplications in methods of this controller
        if ($request->request->has(static::PARAM_SUBMIT_CUSTOMER_CREATE)) {
            return $this->processCustomerCreateCall($request);
        }
        if ($request->request->has(static::PARAM_SUBMIT_PERSIST)) {
            return $this->processPersistCall($request);
        }

        if ($request->request->has(static::PARAM_SUBMIT_RELOAD)) {
            return $this->processReloadCall($request);
        }

        return $this->createDefaultViewResponse($request);
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

        $this->storeFormDataIntoSession($form->getData());

        //TODO: use generateUrl for appending get parameters
        return $this->redirectResponse('/customer/add?redirectUrl=' . urlencode('/offer-gui/create'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processPersistCall(Request $request)
    {
        $offerTransfer = $this->getOfferTransfer($request);

        $form = $this->getFactory()->getOfferForm($offerTransfer, $request);
        $form->handleRequest($request);

        if (($result = $this->processSubmittedForm($request, $form)) !== null) {
            return $result;
        }

        //refresh form after calculations
        $form = $this->getFactory()->getOfferForm($offerTransfer, $request);

        if (($result = $this->persistOffer($request, $offerTransfer)) !== null) {
            return $result;
        }

        return $this->viewResponse([
            'offer' => $offerTransfer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processReloadCall(Request $request)
    {
        return $this->processPersistCall($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function createDefaultViewResponse(Request $request)
    {
        $offerTransfer = $this->getOfferTransfer($request);

        $offerTransfer = $this->processCustomerRedirect($request, $offerTransfer);

        $form = $this->getFactory()->getOfferForm($offerTransfer, $request);
        $form->handleRequest($request);

        return $this->viewResponse([
            'offer' => $offerTransfer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return void
     */
    protected function storeFormDataIntoSession(OfferTransfer $offerTransfer)
    {
        $jsonData = $this->getFactory()->getUtilEncoding()->encodeJson($offerTransfer->toArray());

        $this->getFactory()
            ->getSessionClient()
            ->set(static::SESSION_KEY_OFFER_DATA, $jsonData);
    }

    /**
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    protected function retrieveFormDataFromSession(): OfferTransfer
    {
        $jsonData = $this->getFactory()
            ->getSessionClient()
            ->get(static::SESSION_KEY_OFFER_DATA);

        return $this->getFactory()
            ->getUtilEncoding()
            ->decodeJson($jsonData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array|null
     */
    protected function processSubmittedForm(Request $request, FormInterface $form)
    {
        if (!$form->isSubmitted() || !$form->isValid()) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\OfferTransfer $offerTransfer */
        $offerTransfer = $form->getData();

        $this->getFactory()
            ->createCreateRequestHandler()
            ->addItems($offerTransfer);

        if ($offerTransfer->getQuote()->getItems()->count() <= 0) {
            $this->addErrorMessage(static::ERROR_MESSAGE_ITEMS_NOT_AVAILABLE);

            return $this->viewResponse([
                'offer' => $offerTransfer,
                'form' => $form->createView(),
            ]);
        }

        $this->getFactory()
            ->createCreateRequestHandler()
            ->updateCart($offerTransfer);
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
            $offerTransfer->fromArray(
                $this->getFactory()
                    ->getUtilEncoding()
                    ->decodeJson($offerJson, true)
            );
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
            static::REDIRECT_URL_OFFER_VIEW,
            [EditController::PARAM_ID_OFFER => $offerResponseTransfer->getOffer()->getIdOffer()]
        )->build();

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    protected function processCustomerRedirect(Request $request, OfferTransfer $offerTransfer): OfferTransfer
    {
        if (!$request->query->has(static::PARAM_CUSTOMER_REFERENCE)) {
            return $offerTransfer;
        }

        return (new OfferTransfer())->fromArray(json_decode($this->getFactory()->getSessionClient()->get(static::SESSION_KEY_OFFER_DATA), true));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function persistOffer(Request $request, OfferTransfer $offerTransfer)
    {
        if ($request->request->get(static::PARAM_SUBMIT_PERSIST)) {
            $offerResponseTransfer = $this->getFactory()
                ->getOfferFacade()
                ->createOffer($offerTransfer);

            if ($offerResponseTransfer->getIsSuccessful()) {
                return $this->getSuccessfulRedirect($offerResponseTransfer);
            }
        }
    }
}
