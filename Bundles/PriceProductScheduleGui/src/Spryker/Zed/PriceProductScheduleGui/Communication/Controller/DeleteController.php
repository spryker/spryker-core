<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleDeleteFormDataProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    protected const PARAM_ID_PRICE_PRODUCT_SCHEDULE = 'id-price-product-schedule';
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    protected const PARAM_ID_PRODUCT = 'id-product';
    protected const PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST = 'id-price-product-schedule-list';
    protected const PARAM_TEMPLATE_ID_PRICE_PRODUCT_SCHEDULE = 'idPriceProductSchedule';
    protected const PARAM_TEMPLATE_FORM = 'form';
    protected const PARAM_REDIRECT_URL = 'redirectUrl';
    protected const SUCCESS_MESSAGE = 'Scheduled price was successfully removed';
    protected const REDIRECT_URL_MAIN_PAGE = '/';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $priceProductScheduleTransfer = $this->createPriceProductScheduleTransfer($request);

        $redirectUrl = $this->makeRedirectUrl($request);
        $dataProvider = $this->getFactory()
            ->createPriceProductScheduleDeleteFormDataProvider();
        $form = $this->getFactory()
            ->createPriceProductScheduleDeleteForm(
                $dataProvider,
                $priceProductScheduleTransfer,
                $redirectUrl
            );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleSubmitForm($form);
        }

        return $this->viewResponse([
            static::PARAM_TEMPLATE_FORM => $form->createView(),
            static::PARAM_TEMPLATE_ID_PRICE_PRODUCT_SCHEDULE => $priceProductScheduleTransfer->getIdPriceProductSchedule(),
            static::PARAM_REDIRECT_URL => $redirectUrl,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    protected function createPriceProductScheduleTransfer(Request $request): PriceProductScheduleTransfer
    {
        $idPriceProductSchedule = $this->castId($request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE));

        return (new PriceProductScheduleTransfer())->setIdPriceProductSchedule($idPriceProductSchedule);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(FormInterface $form): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer */
        $priceProductScheduleTransfer = $form->getData();
        $priceProductScheduleTransfer->requireIdPriceProductSchedule();
        $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->removeAndApplyPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        $this->addSuccessMessage(static::SUCCESS_MESSAGE);

        $redirectUrl = $form->getConfig()
            ->getOption(PriceProductScheduleDeleteFormDataProvider::OPTION_REDIRECT_URL);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function makeRedirectUrl(Request $request): string
    {
        $idProductAbstract = $request->query->get(static::PARAM_ID_PRODUCT_ABSTRACT);
        $idProduct = $request->query->get(static::PARAM_ID_PRODUCT);
        $idPriceProductScheduleList = $request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST);

        $priceProductScheduleRedirectTransfer = (new PriceProductScheduleRedirectTransfer())
            ->setIdPriceProductScheduleList($idPriceProductScheduleList)
            ->setIdProductAbstract($idProductAbstract)
            ->setIdProduct($idProduct);

        $priceProductScheduleRedirectTransfer = $this->getFactory()
            ->createPriceProductScheduleRedirectStrategyResolver()
            ->resolve($priceProductScheduleRedirectTransfer);

        return $priceProductScheduleRedirectTransfer->getRedirectUrl() ?? static::REDIRECT_URL_MAIN_PAGE;
    }
}
