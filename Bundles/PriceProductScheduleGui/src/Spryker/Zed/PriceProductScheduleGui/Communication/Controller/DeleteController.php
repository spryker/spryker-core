<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    protected const PARAM_ID_PRICE_PRODUCT_SCHEDULE = 'id-price-product-schedule';
    protected const PARAM_TEMPLATE_ID_PRICE_PRODUCT_SCHEDULE = 'idPriceProductSchedule';
    protected const PARAM_REDIRECT_URL = 'redirectUrl';
    protected const SUCCESS_MESSAGE = 'Scheduled price was successfully removed';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $idPriceProductSchedule = $this->castId($request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE));

        return $this->viewResponse([
            static::PARAM_TEMPLATE_ID_PRICE_PRODUCT_SCHEDULE => $idPriceProductSchedule,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request): RedirectResponse
    {
        $redirectUrl = $request->query->get(static::PARAM_REDIRECT_URL);
        $idPriceProductSchedule = $this->castId($request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE));

        $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->removeAndApplyPriceProductSchedule($idPriceProductSchedule);

        $this->addSuccessMessage(static::SUCCESS_MESSAGE);

        return $this->redirectResponse($redirectUrl);
    }
}
