<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Controller;

use Exception;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 */
class DeleteRateController extends AbstractController
{
    protected const PARAM_REQUEST_ID_TAX_RATE = 'id-tax-rate';
    protected const PARAM_TEMPLATE_ID_TAX_RATE = 'idTaxRate';
    protected const URL_LIST_TAX_RATE = '/tax/rate/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idTaxRate = $this->castId($request->query->get(static::PARAM_REQUEST_ID_TAX_RATE));

        try {
            $this->getFacade()->getTaxRate($idTaxRate);
        } catch (Exception $exception) {
            $this->addErrorMessage('Tax Rate does not exist');

            return $this->redirectResponse(Url::generate(static::URL_LIST_TAX_RATE)->build());
        }

        return $this->viewResponse([
            static::PARAM_TEMPLATE_ID_TAX_RATE => $idTaxRate,
        ]);
    }
}
