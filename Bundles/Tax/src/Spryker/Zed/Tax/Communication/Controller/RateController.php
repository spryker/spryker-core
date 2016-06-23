<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Controller;

use Spryker\Shared\Url\Url;
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
        $taxRateFormDataProvider = $this->getFactory()->createTaxRateFormDataProvider();

        $form = $this->getFactory()->createTaxRateForm($taxRateFormDataProvider);
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
        $taxRateFormDataProvider = $this->getFactory()->createTaxRateFormDataProvider($taxRateTransfer);

        $form = $this->getFactory()->createTaxRateForm($taxRateFormDataProvider);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $taxRateTransfer = $form->getData();
            $taxRateTransfer->setIdTaxRate($idTaxRate);
            $affectedRows = $this->getFacade()->updateTaxRate($taxRateTransfer);
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
    public function viewAction(Request $request)
    {
        $idTaxRate = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_RATE));

        $taxRateTransfer = $this->getFacade()->getTaxRate($idTaxRate);

        return [
            'taxRate' => $taxRateTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idTaxRate = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_RATE));

        $this->getFacade()->deleteTaxRate($idTaxRate);

        $this->addSuccessMessage('Tax rate removed.');

        return $this->redirectResponse(Url::generate('/tax/rate/list')->build());
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $table = $this->getFactory()->createTaxRateTable();

        return [
            'taxRateTable' => $table->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listTableAction(Request $request)
    {
        $table = $this->getFactory()->createTaxRateTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }
}
