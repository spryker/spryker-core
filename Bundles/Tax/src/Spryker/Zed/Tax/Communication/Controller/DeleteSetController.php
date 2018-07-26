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
 */
class DeleteSetController extends AbstractController
{
    protected const PARAM_REQUEST_ID_TAX_SET = 'id-tax-set';
    protected const PARAM_TEMPLATE_ID_TAX_SET = 'idTaxSet';
    protected const URL_LIST_TAX_SET = '/tax/set/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->get(static::PARAM_REQUEST_ID_TAX_SET));

        try {
            $this->getFacade()->getTaxSet($idTaxSet);
        } catch (PropelException $exception) {
            $this->addErrorMessage('Tax Set does not exist');

            return $this->redirectResponse(Url::generate(static::URL_LIST_TAX_SET)->build());
        }

        return $this->viewResponse([
            static::PARAM_TEMPLATE_ID_TAX_SET => $idTaxSet,
        ]);
    }
}
