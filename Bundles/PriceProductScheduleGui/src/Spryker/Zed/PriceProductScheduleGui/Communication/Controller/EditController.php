<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    protected const PARAM_ID_PRICE_PRODUCT_SCHEDULE = 'id-price-product-schedule';
    protected const TITLE_PRODUCT_ABSTRACT_PATTERN = 'Edit Product Abstract: %s';
    protected const TITLE_PRODUCT_CONCRETE_PATTERN = 'Edit Product Concrete: %s';
    protected const KEY_HEADER_REFERER = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idPriceProductSchedule = $this->castId($request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE));

        $priceProductScheduleTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->findPriceProductScheduleById($idPriceProductSchedule);

        if ($priceProductScheduleTransfer === null) {
            return $this->redirectResponse($request->headers->get(static::KEY_HEADER_REFERER));
        }

        $priceProductScheduleFormDataProvider = $this->getFactory()
            ->createPriceProductScheduleFormDataProvider($priceProductScheduleTransfer);
        $form = $this->getFactory()->createPriceProductScheduleForm($priceProductScheduleFormDataProvider);

        return $this->viewResponse([
            'form' => $form->createView(),
            'title' => $this->extractTitleFromPriceProductScheduleTransfer($priceProductScheduleTransfer),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return string
     */
    protected function extractTitleFromPriceProductScheduleTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): string {
        $priceProductScheduleTransfer->requirePriceProduct();
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
        if ($idProductAbstract !== null) {
            return sprintf(static::TITLE_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
        }

        $idProductConcrete = $priceProductTransfer->getIdProduct();

        return sprintf(static::TITLE_PRODUCT_CONCRETE_PATTERN, $idProductConcrete);
    }
}
