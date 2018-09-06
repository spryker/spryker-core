<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\MerchantRelationshipMinimumOrderValueGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $merchantRelationshipMinimumOrderValueTable = $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueTable();

        return $this->viewResponse([
            'merchantRelationships' => $merchantRelationshipMinimumOrderValueTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $merchantRelationshipMinimumOrderValueTable = $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueTable();

        return $this->jsonResponse($merchantRelationshipMinimumOrderValueTable->fetchData());
    }
}
