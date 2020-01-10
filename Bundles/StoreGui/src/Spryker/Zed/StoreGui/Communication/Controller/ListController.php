<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\StoreGui\Communication\StoreGuiCommunicationFactory getFactory()
 */
class ListController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $storeTable = $this->getFactory()->createStoreTable();

        return $this->viewResponse([
            'storeTable' => $storeTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $storeTable = $this->getFactory()->createStoreTable();

        return $this->jsonResponse(
            $storeTable->fetchData()
        );
    }
}
