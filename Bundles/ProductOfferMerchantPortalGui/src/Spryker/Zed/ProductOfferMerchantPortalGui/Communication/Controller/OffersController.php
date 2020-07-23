<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class OffersController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'productOfferTableConfiguration' => $this->getFactory()->createProductOfferGuiTableConfigurationProvider()->getConfiguration(),
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
            $this->getFactory()->createProductOfferTableDataProvider(),
            $this->getFactory()->createProductOfferGuiTableConfigurationProvider()->getConfiguration(),
            $this->getFactory()->getLocaleFacade()->getCurrentLocale()
        );

        return $jsonResponse;
    }
}
