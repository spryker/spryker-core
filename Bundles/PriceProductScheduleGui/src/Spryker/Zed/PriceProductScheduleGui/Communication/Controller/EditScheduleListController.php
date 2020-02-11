<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class EditScheduleListController extends AbstractController
{
    public const PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST = 'id-price-product-schedule-list';
    protected const REDIRECT_URL = '/price-product-schedule-gui/import';
    protected const MESSAGE_SUCCESS = 'Scheduled price list has been successfully saved';
    protected const URL_PATTERN_EDIT_LIST = '/price-product-schedule-gui/edit-schedule-list?id-price-product-schedule-list=%s';
    protected const FORMAT_DATE_TIME = 'Y-m-d e H:i:s';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idPriceProductScheduleList = $this->castId(
            $request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST)
        );
        $priceProductScheduleListTransfer = $this->createPriceProductScheduleListTransfer($idPriceProductScheduleList);

        $priceProductScheduleListResponseTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->findPriceProductScheduleList($priceProductScheduleListTransfer);

        if (!$priceProductScheduleListResponseTransfer->getIsSuccess()) {
            $this->setErrors($priceProductScheduleListResponseTransfer);

            return $this->redirectResponse(static::REDIRECT_URL);
        }

        $priceProductScheduleListResponseTransfer->requirePriceProductScheduleList();

        $form = $this->getForm($priceProductScheduleListResponseTransfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleSubmitForm($form);
        }

        $priceProductScheduleTable = $this->getFactory()
            ->createPriceProductScheduleTableForEditList($idPriceProductScheduleList);

        $priceProductScheduleListTransfer = $priceProductScheduleListResponseTransfer->getPriceProductScheduleList();
        $priceProductScheduleListTransfer = $this->formatCreatedAt($priceProductScheduleListTransfer);

        return $this->viewResponse([
            'priceProductScheduleList' => $priceProductScheduleListTransfer,
            'priceProductScheduleTable' => $priceProductScheduleTable->render(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    protected function formatCreatedAt(PriceProductScheduleListTransfer $priceProductScheduleListTransfer): PriceProductScheduleListTransfer
    {
        $createdAt = $priceProductScheduleListTransfer->getCreatedAt();
        $dateTime = new DateTime($createdAt);
        $storeTransfer = $this->getFactory()
            ->getStoreFacade()
            ->getCurrentStore();
        $timezone = new DateTimeZone($storeTransfer->getTimezone());
        $dateTime->setTimezone($timezone);

        return $priceProductScheduleListTransfer->setCreatedAt($dateTime->format(static::FORMAT_DATE_TIME));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer $priceProductScheduleListResponseTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getForm(PriceProductScheduleListResponseTransfer $priceProductScheduleListResponseTransfer): FormInterface
    {
        $dataProvider = $this->getFactory()
            ->createPriceProductScheduleListFormDataProvider();

        return $this->getFactory()
            ->createPriceProductScheduleListForm(
                $dataProvider,
                $priceProductScheduleListResponseTransfer->getPriceProductScheduleList()
            );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(FormInterface $form): RedirectResponse
    {
        $priceProductScheduleListResponseTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->updatePriceProductScheduleList($form->getData());

        $priceProductScheduleListResponseTransfer->requirePriceProductScheduleList();
        $priceProductScheduleListTransfer = $priceProductScheduleListResponseTransfer->getPriceProductScheduleList();
        $priceProductScheduleListTransfer->requireIdPriceProductScheduleList();
        $idPriceProductScheduleList = $priceProductScheduleListTransfer->getIdPriceProductScheduleList();

        if ($priceProductScheduleListResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS);

            return $this->redirectResponse(sprintf(static::URL_PATTERN_EDIT_LIST, $idPriceProductScheduleList));
        }

        $this->setErrors($priceProductScheduleListResponseTransfer);

        return $this->redirectResponse(sprintf(static::URL_PATTERN_EDIT_LIST, $idPriceProductScheduleList));
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    protected function createPriceProductScheduleListTransfer(int $idPriceProductScheduleList): PriceProductScheduleListTransfer
    {
        return (new PriceProductScheduleListTransfer())
            ->setIdPriceProductScheduleList($idPriceProductScheduleList);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer $priceProductScheduleListResponseTransfer
     *
     * @return void
     */
    protected function setErrors(PriceProductScheduleListResponseTransfer $priceProductScheduleListResponseTransfer): void
    {
        foreach ($priceProductScheduleListResponseTransfer->getErrors() as $priceProductScheduleListErrorTransfer) {
            $this->addErrorMessage($priceProductScheduleListErrorTransfer->getMessage());
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $idPriceProductScheduleList = $this->castId(
            $request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST)
        );

        $priceProductScheduleTable = $this->getFactory()
            ->createPriceProductScheduleTableForEditList($idPriceProductScheduleList);

        return $this->jsonResponse(
            $priceProductScheduleTable->fetchData()
        );
    }
}
