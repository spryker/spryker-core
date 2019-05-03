<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ContentProductSetGui\Communication\ContentProductSetGuiCommunicationFactory getFactory()
 */
class ProductSetController extends AbstractController
{
    public const PARAM_ID = 'id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productSetSelectedTableAction(Request $request): JsonResponse
    {
        $idProductSet = $request->query->get(static::PARAM_ID);

        return $this->jsonResponse(
            $this->getFactory()->createProductSetSelectedTable($idProductSet)->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productSetViewTableAction(Request $request): JsonResponse
    {
        return $this->jsonResponse(
            $this->getFactory()->createProductSetViewTable()->fetchData()
        );
    }
}
