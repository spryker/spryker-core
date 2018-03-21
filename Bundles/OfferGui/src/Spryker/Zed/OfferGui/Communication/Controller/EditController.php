<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EditController extends AbstractController
{
    public const PARAM_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $request->get(static::PARAM_ID_SALES_ORDER);

        dump($idSalesOrder);
        exit;

        return $this->viewResponse([]);
    }
}
