<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 */
class DeleteSetController extends AbstractController
{
    protected const PARAM_REQUEST_ID_TAX_SET = 'id-tax-set';
    protected const PARAM_TEMPLATE_ID_TAX_SET = 'idTaxSet';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->get(static::PARAM_REQUEST_ID_TAX_SET));

        return $this->viewResponse([
            static::PARAM_TEMPLATE_ID_TAX_SET => $idTaxSet,
        ]);
    }
}
