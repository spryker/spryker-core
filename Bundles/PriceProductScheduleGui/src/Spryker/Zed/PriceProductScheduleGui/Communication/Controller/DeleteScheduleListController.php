<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class DeleteScheduleListController extends AbstractController
{
    protected const REDIRECT_URL = '/price-product-schedule-gui/import';
    protected const SUCCESS_MESSAGE = 'Scheduled price list was successfully removed';

    protected const PARAM_TEMPLATE_PRICE_PRODUCT_SCHEDULE_LIST = 'priceProductScheduleList';
    protected const PARAM_TEMPLATE_FORM = 'form';
    protected const PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST = 'id-price-product-schedule-list';

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
        $priceProductScheduleListResponseTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->findPriceProductScheduleList($this->createPriceProductScheduleListTransfer($idPriceProductScheduleList));

        if (!$priceProductScheduleListResponseTransfer->getIsSuccess()) {
            $this->setErrors($priceProductScheduleListResponseTransfer);

            return $this->redirectResponse(static::REDIRECT_URL);
        }

        $form = $this->getFactory()
            ->createPriceProductScheduleListDeleteForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleSubmitForm($idPriceProductScheduleList);
        }

        return $this->viewResponse([
            static::PARAM_TEMPLATE_FORM => $form->createView(),
            static::PARAM_TEMPLATE_PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListResponseTransfer->getPriceProductScheduleList(),
        ]);
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(int $idPriceProductScheduleList): RedirectResponse
    {
        $priceProductScheduleListResponseTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->removePriceProductScheduleList($idPriceProductScheduleList);

        if (!$priceProductScheduleListResponseTransfer->getIsSuccess()) {
            $this->setErrors($priceProductScheduleListResponseTransfer);

            return $this->redirectResponse(static::REDIRECT_URL);
        }

        $this->addSuccessMessage(static::SUCCESS_MESSAGE);

        return $this->redirectResponse(static::REDIRECT_URL);
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
     * @param int $idPriceProductScheduleList
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    protected function createPriceProductScheduleListTransfer(int $idPriceProductScheduleList): PriceProductScheduleListTransfer
    {
        return (new PriceProductScheduleListTransfer())
            ->setIdPriceProductScheduleList($idPriceProductScheduleList);
    }
}
