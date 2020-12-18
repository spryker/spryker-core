<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class ListController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $categoryTable = $this->getFactory()
            ->createCategoryTable();

        return $this->viewResponse([
            'categoryTable' => $categoryTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $categoryTable = $this
            ->getFactory()
            ->createCategoryTable();

        return $this->jsonResponse(
            $categoryTable->fetchData()
        );
    }
}
