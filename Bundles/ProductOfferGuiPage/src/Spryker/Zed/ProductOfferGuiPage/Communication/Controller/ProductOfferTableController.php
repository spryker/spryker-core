<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferGuiPage\Communication\ProductOfferGuiPageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface getRepository()
 */
class ProductOfferTableController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $productOfferTable = $this->getFactory()->createProductOfferTable();

        return $this->viewResponse(
            $productOfferTable->getConfiguration()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDataAction(Request $request): JsonResponse
    {
        $productOfferTable = $this->getFactory()->createProductOfferTable();

        return new JsonResponse(
            $productOfferTable->getData($request)
        );
    }
}
