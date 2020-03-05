<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferGuiPage\Communication\ProductOfferGuiPageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface getFacade()
 */
class ProductListTableController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function configAction(): JsonResponse
    {
        $tableConfig = $this->getFactory()->createProductListTable()->getConfiguration();

        return new JsonResponse($tableConfig);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $productListTable = $this->getFactory()->createProductListTable();

        return new JsonResponse(
            $productListTable->getData($request)
        );
    }
}
