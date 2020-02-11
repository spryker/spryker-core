<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class DownloadScheduleListController extends AbstractController
{
    protected const PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST = 'id-price-product-schedule-list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function indexAction(Request $request): StreamedResponse
    {
        $idPriceProductSchedulelist = $request->query->get(static::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST);

        $response = $this->getFactory()
            ->createPriceProductScheduleCsvExporter()
            ->exportToCsvFile($idPriceProductSchedulelist);

        return $response->send();
    }
}
