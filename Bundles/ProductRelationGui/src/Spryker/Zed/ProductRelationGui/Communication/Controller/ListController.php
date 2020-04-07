<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\ProductRelationGui\Communication\ProductRelationGuiCommunicationFactory getFactory()
 */
class ListController extends AbstractController
{
    /**
     * @return string[]
     */
    public function indexAction(): array
    {
        $productRelationTable = $this->getFactory()
            ->createProductRelationTable();

        return [
            'productRelationTable' => $productRelationTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $productRelationTable = $this->getFactory()
            ->createProductRelationTable();

        return $this->jsonResponse($productRelationTable->fetchData());
    }
}
