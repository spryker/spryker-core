<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface getRepository()
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 */
class DeleteRateController extends AbstractController
{
    protected const PARAM_REQUEST_ID_TAX_RATE = 'id-tax-rate';
    protected const PARAM_TEMPLATE_ID_TAX_RATE = 'idTaxRate';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idTaxRate = $this->castId($request->query->get(static::PARAM_REQUEST_ID_TAX_RATE));

        return $this->viewResponse([
            static::PARAM_TEMPLATE_ID_TAX_RATE => $idTaxRate,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request)
    {
        $idTaxRate = $this->castId($request->query->getInt(static::PARAM_REQUEST_ID_TAX_RATE));

        $this->getFacade()->deleteTaxRate($idTaxRate);
        $this->addSuccessMessage('Tax rate %d was deleted successfully.', ['%d' => $idTaxRate]);

        return $this->redirectResponse(Url::generate('/tax/rate/list')->build());
    }
}
