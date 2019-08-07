<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ContentProductGui\Communication\ContentProductGuiCommunicationFactory getFactory()
 */
class ProductAbstractController extends AbstractController
{
    public const PARAM_IDS = 'ids';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAbstractSelectedTableAction(Request $request): JsonResponse
    {
        $idProductAbstracts = $request->query->get(static::PARAM_IDS, []);

        return $this->jsonResponse(
            $this->getFactory()->createProductAbstractSelectedTable($idProductAbstracts)->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAbstractViewTableAction(Request $request): JsonResponse
    {
        return $this->jsonResponse(
            $this->getFactory()->createProductAbstractViewTable()->fetchData()
        );
    }
}
