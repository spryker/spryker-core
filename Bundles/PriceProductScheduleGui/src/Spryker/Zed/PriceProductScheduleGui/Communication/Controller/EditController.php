<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    protected const PARAM_ID_PRICE_PRODUCT_SCHEDULE = 'id-price-product-schedule';
    protected const PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST = 'id-price-product-schedule-list';

    protected const KEY_HEADER_REFERER = 'referer';
    protected const MESSAGE_SUCCESS = 'Scheduled price has been successfully saved';

    protected const KEY_FORM = 'form';
    protected const KEY_TITLE = 'title';
    protected const KEY_REDIRECT_URL = 'redirectUrl';
    protected const KEY_TIMEZONE_TEXT = 'timezoneText';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idPriceProductSchedule = $this->castId($request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE));
        $idPriceProductScheduleList = $request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST);

        $priceProductScheduleTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->findPriceProductScheduleById($idPriceProductSchedule);

        if ($priceProductScheduleTransfer === null) {
            return $this->redirectResponse($request->headers->get(static::KEY_HEADER_REFERER, '/'));
        }

        $priceProductScheduleFormDataProvider = $this->getFactory()
            ->createPriceProductScheduleFormDataProvider();
        $form = $this->getFactory()
            ->createPriceProductScheduleForm($priceProductScheduleFormDataProvider, $priceProductScheduleTransfer);
        $form->handleRequest($request);
        $priceProductScheduleRedirectTransfer = $this->createPriceProductScheduleRedirectTransfer($priceProductScheduleTransfer, $idPriceProductScheduleList);

        $viewData = $this->prepareViewResponseData($form, $priceProductScheduleTransfer, $priceProductScheduleRedirectTransfer);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleSubmitForm($form, $viewData[static::KEY_REDIRECT_URL]);
        }

        return $this->viewResponse($viewData);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     * @param int|null $idPriceProductScheduleList
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer
     */
    protected function createPriceProductScheduleRedirectTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer,
        ?int $idPriceProductScheduleList
    ): PriceProductScheduleRedirectTransfer {
        $priceProductTransfer = $priceProductScheduleTransfer->requirePriceProduct()
            ->getPriceProduct();

        return (new PriceProductScheduleRedirectTransfer())
            ->setIdPriceProductScheduleList($idPriceProductScheduleList)
            ->setIdProduct($priceProductTransfer->getIdProduct())
            ->setIdProductAbstract($priceProductTransfer->getIdProductAbstract());
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer
     *
     * @return array
     */
    protected function prepareViewResponseData(
        FormInterface $form,
        PriceProductScheduleTransfer $priceProductScheduleTransfer,
        PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer
    ): array {
        $dataExtractor = $this->getFactory()
            ->createPriceProductScheduleDataExtractor();
        $title = $dataExtractor
            ->extractTitleFromPriceProductScheduleTransfer($priceProductScheduleTransfer);
        $timezoneText = $dataExtractor
            ->extractTimezoneTextFromPriceProductScheduledTransfer($priceProductScheduleTransfer);
        $redirectUrl = $dataExtractor->extractRedirectUrlFromPriceProductSchedule($priceProductScheduleRedirectTransfer);

        return [
            static::KEY_FORM => $form->createView(),
            static::KEY_TITLE => $title,
            static::KEY_REDIRECT_URL => $redirectUrl,
            static::KEY_TIMEZONE_TEXT => $timezoneText,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(FormInterface $form, string $redirectUrl): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer */
        $priceProductScheduleTransfer = $form->getData();
        $priceProductScheduleResponseTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->updateAndApplyPriceProductSchedule($priceProductScheduleTransfer);

        if ($priceProductScheduleResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS);

            return $this->redirectResponse($redirectUrl);
        }

        foreach ($priceProductScheduleResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessage());
        }

        return $this->redirectResponse($redirectUrl);
    }
}
