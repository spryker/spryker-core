<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    protected const MESSAGE_SUCCESS = 'Scheduled price has been successfully saved';
    protected const KEY_TITLE = 'title';
    protected const KEY_FORM = 'form';
    protected const KEY_REDIRECT_URL = 'redirectUrl';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $priceProductScheduleFormDataProvider = $this->getFactory()->createPriceProductScheduleFormDataProvider();
        $form = $this->getFactory()->createPriceProductScheduleForm($priceProductScheduleFormDataProvider);
        $form->handleRequest($request);
        $requestReader = $this->getFactory()->createRequestReader();
        $redirectUrl = $requestReader->getRedirectUrlFromRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleSubmitForm($form, $redirectUrl, $request);
        }

        return $this->viewResponse([
            static::KEY_FORM => $form->createView(),
            static::KEY_TITLE => $requestReader->getTitleFromRequest($request),
            static::KEY_REDIRECT_URL => $redirectUrl,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string $redirectUrl
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(FormInterface $form, string $redirectUrl, Request $request): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer */
        $priceProductScheduleTransfer = $form->getData();
        $priceProductScheduleTransfer = $this->setProductIdentifierFromRequest($request, $priceProductScheduleTransfer);
        $priceProductScheduleResponseTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->createAndApplyPriceProductSchedule($priceProductScheduleTransfer);

        if ($priceProductScheduleResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS);

            return $this->redirectResponse($redirectUrl);
        }

        foreach ($priceProductScheduleResponseTransfer->getErrors() as $priceProductScheduleErrorTransfer) {
            $this->addErrorMessage($priceProductScheduleErrorTransfer->getMessage());
        }

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    protected function setProductIdentifierFromRequest(Request $request, PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleTransfer
    {
        $priceProductScheduleTransfer->requirePriceProduct();
        $requestParams = $this->getFactory()->createRequestReader()->getQueryParamsFromRequest($request);
        $priceProductScheduleTransfer->getPriceProduct()->fromArray($requestParams, true);

        return $priceProductScheduleTransfer;
    }
}
