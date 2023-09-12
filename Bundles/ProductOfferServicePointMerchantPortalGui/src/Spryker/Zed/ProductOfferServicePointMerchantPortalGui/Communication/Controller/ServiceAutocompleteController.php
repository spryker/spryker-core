<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\ProductOfferServicePointMerchantPortalGuiCommunicationFactory getFactory()
 */
class ServiceAutocompleteController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_SERVICE_POINT = 'idServicePoint';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idServicePoint = (int)$request->query->get(static::REQUEST_PARAM_ID_SERVICE_POINT);

        $serviceSelectOptions = $this->getFactory()->createServiceDataProvider()->getServiceSelectOptions($idServicePoint);

        return $this->jsonResponse($serviceSelectOptions);
    }
}
