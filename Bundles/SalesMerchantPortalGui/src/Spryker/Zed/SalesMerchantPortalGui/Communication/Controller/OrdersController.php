<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class OrdersController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'merchantOrderTableConfiguration' => $this->getFactory()->createMerchantOrderGuiTableConfigurationProvider()->getConfiguration(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableDataAction(Request $request): JsonResponse
    {
        $guiTableFacade = $this->getFactory()->getGuiTableFacade();
        $guiTableConfigurationTransfer = $this->getFactory()
            ->createMerchantOrderGuiTableConfigurationProvider()
            ->getConfiguration();
        $guiTableDataRequestTransfer = $guiTableFacade->buildGuiTableDataRequest(
            $request->query->all(),
            $guiTableConfigurationTransfer
        );
        $guiTableDataResponseTransfer = $this->getFactory()
            ->createMerchantOrderTableDataProvider()
            ->getData($guiTableDataRequestTransfer);

        return $this->jsonResponse(
            $guiTableFacade->formatGuiTableDataResponse($guiTableDataResponseTransfer, $guiTableConfigurationTransfer)
        );
    }
}
