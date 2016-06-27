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
 * @method \Spryker\Zed\Tax\Business\ShipmentFacade getFacade()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainer getQueryContainer()
 */
class SetController extends AbstractController
{

    const PARAM_URL_ID_TAX_SET = 'id-tax-set';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $taxSetFormDataProvider = $this->getFactory()->createTaxSetFormDataProvider();

        $taxSetForm = $this->getFactory()->createTaxSetForm($taxSetFormDataProvider);
        $taxSetForm->handleRequest($request);

        if ($taxSetForm->isValid()) {
            $taxSetTransfer = $this->getFacade()->createTaxSet($taxSetForm->getData());
            if ($taxSetTransfer->getIdTaxSet()) {
                $this->addSuccessMessage('Tax set succefully created.');
            }
        }

        return [
          'form' => $taxSetForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_SET));

        $taxSetTransfer = $this->getFacade()->getTaxSet($idTaxSet);

        $taxSetFormDataProvider = $this->getFactory()->createTaxSetFormDataProvider($taxSetTransfer);

        $taxSetForm = $this->getFactory()->createTaxSetForm($taxSetFormDataProvider);
        $taxSetForm->handleRequest($request);

        if ($taxSetForm->isValid()) {
            $taxSetTransfer = $taxSetForm->getData();
            $taxSetTransfer->setIdTaxSet($idTaxSet);

            $rowsAffected = $this->getFacade()->updateTaxSet($taxSetForm->getData());
            if ($rowsAffected > 0) {
                $this->addSuccessMessage('Tax set succefully updated.');
                return $this->redirectResponse(Url::generate('/tax/set/list')->build());
            } else {
                $this->addErrorMessage('No updates saved.');
            }
        }

        return [
            'form' => $taxSetForm->createView(),
            'taxSet' => $taxSetTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_SET));

        $taxSetTransfer = $this->getFacade()->getTaxSet($idTaxSet);

        return [
            'taxSet' => $taxSetTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_SET));

        $removed = $this->getFacade()->deleteTaxSet($idTaxSet);

        if ($removed) {
            $this->addSuccessMessage('Tax set removed.');
        } else {
            $this->addErrorMessage('Failed to remove tax set.');
        }

        return $this->redirectResponse(Url::generate('/tax/set/list')->build());
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $table = $this->getFactory()->createTaxSetTable();

        return [
            'taxSetTable' => $table->render(),
        ];
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listTableAction()
    {
        $table = $this->getFactory()->createTaxSetTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
