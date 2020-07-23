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
     * @phpstan-return array<mixed>
     *
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
        /** @var \Symfony\Component\HttpFoundation\JsonResponse $jsonResponse */
        $jsonResponse = $this->getFactory()->getGuiTableHttpDataRequestHandler()->handleGetDataRequest(
            $request,
            $this->getFactory()->createMerchantOrderTableDataProvider(),
            $this->getFactory()->createMerchantOrderGuiTableConfigurationProvider()->getConfiguration(),
            $this->getFactory()->getLocaleFacade()->getCurrentLocale()
        );

        return $jsonResponse;
    }
}
