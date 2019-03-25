<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Controller;

use Propel\Runtime\Exception\PropelException;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface getRepository()
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 */
class DeleteSetController extends AbstractController
{
    protected const PARAM_REQUEST_ID_TAX_SET = 'id-tax-set';
    protected const PARAM_TEMPLATE_ID_TAX_SET = 'idTaxSet';

    protected const MESSAGE_SUCCESS_DELETE_TAX_SET = 'The tax set has been deleted';

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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->getInt(static::PARAM_REQUEST_ID_TAX_SET));

        try {
            $this->getFacade()->deleteTaxSet($idTaxSet);
            $this->addSuccessMessage(static::MESSAGE_SUCCESS_DELETE_TAX_SET);
        } catch (PropelException $e) {
            $this->addErrorMessage('Could not delete tax set. Is it assigned to product or shipping method?');
        }

        return $this->redirectResponse(Url::generate('/tax/set/list')->build());
    }
}
