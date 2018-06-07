<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class ListMerchantController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $merchantTable = $this->getFactory()
            ->createMerchantTable();

        return $this->viewResponse([
            'merchants' => $merchantTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createMerchantTable();

        return $this->jsonResponse($table->fetchData());
    }
}
