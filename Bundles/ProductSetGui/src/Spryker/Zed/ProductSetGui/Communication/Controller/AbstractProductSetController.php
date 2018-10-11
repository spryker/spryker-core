<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 */
abstract class AbstractProductSetController extends AbstractController
{
    public const PARAM_ID = 'id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productTableAction(Request $request)
    {
        $idProductSet = null;
        if ($request->query->has(static::PARAM_ID)) {
            $idProductSet = $this->castId($request->query->get(static::PARAM_ID));
        }

        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        return $this->jsonResponse(
            $this->getFactory()
                ->createProductTable($localeTransfer, $idProductSet)
                ->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAbstractSetTableAction(Request $request)
    {
        $idProductSet = $this->castId($request->query->get(static::PARAM_ID));

        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        return $this->jsonResponse(
            $this->getFactory()
                ->createProductAbstractSetUpdateTable($localeTransfer, $idProductSet)
                ->fetchData()
        );
    }
}
