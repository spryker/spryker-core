<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 * @method \Spryker\Zed\Tax\Business\TaxFacade getFacade()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainer getQueryContainer()
 */
class RateController extends AbstractController
{
    const PARAM_URL_ID_TAX_RATE = 'id-tax-rate';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $form = $this->getFactory()->createTaxRateForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $taxRateTransfer = $this->getFacade()->createTaxRate($form->getData());
            if ($taxRateTransfer->getIdTaxRate()) {
                $this->addSuccessMessage('Tax rate succesfully created.');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idTaxRate = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_RATE));

        $taxRateTransfer = $this->getFacade()->getTaxRate($idTaxRate);

        $form = $this->getFactory()->createTaxRateForm($taxRateTransfer);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $affectedRows = $this->getFacade()->updateTaxRate($form->getData());
            if ($affectedRows > 0) {
                $this->addSuccessMessage('Tax rate succesfully updated.');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        return [];
    }
}
