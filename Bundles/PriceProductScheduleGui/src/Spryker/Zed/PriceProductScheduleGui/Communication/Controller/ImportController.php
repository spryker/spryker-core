<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class ImportController extends AbstractController
{
    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $factory = $this->getFactory();
        $priceProductScheduleImportForm = $factory->getPriceProductScheduleImportForm();
        $priceProductScheduleListTable = $factory->createPriceProductScheduleListTable();

        return $this->viewResponse([
            'importForm' => $priceProductScheduleImportForm->createView(),
            'priceProductScheduleListTableView' => $priceProductScheduleListTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $priceProductScheduleTable = $this->getFactory()->createPriceProductScheduleListTable();

        return $this->jsonResponse(
            $priceProductScheduleTable->fetchData()
        );
    }
}
