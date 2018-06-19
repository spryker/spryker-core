<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Business\ProductPackagingUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Communication\ProductPackagingUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $productPackagingUnitTypeTable = $this->getFactory()->createProductPackagingUnitTypeTable();

        return $this->viewResponse([
            'ProductPackagingUnitTypeTable' => $productPackagingUnitTypeTable->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $productPackagingUnitTypeTable = $this->getFactory()->createProductPackagingUnitTypeTable();

        return $this->jsonResponse(
            $productPackagingUnitTypeTable->fetchData()
        );
    }
}
