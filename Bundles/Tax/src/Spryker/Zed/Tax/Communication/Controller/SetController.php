<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Controller;

use Propel\Runtime\Exception\PropelException;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Tax\Business\Model\Exception\DuplicateResourceException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 */
class SetController extends AbstractController
{
    const PARAM_URL_ID_TAX_SET = 'id-tax-set';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $taxSetFormDataProvider = $this->getFactory()->createTaxSetFormDataProvider();

        $taxSetForm = $this->getFactory()->getTaxSetForm($taxSetFormDataProvider);

        if ($request->request->count() > 0) {
            $taxSetForm->handleRequest($request);

            if ($taxSetForm->isSubmitted()) {
                if (!$taxSetForm->isValid()) {
                    $this->addErrorMessage(TaxConstants::ERROR_MESSAGE_TAX_SET_CREATE_ERROR);
                    return [
                        'form' => $taxSetForm->createView(),
                    ];
                }
                try {
                    $taxSetTransfer = $this->getFacade()->createTaxSet($taxSetForm->getData());
                    $this->addSuccessMessage(
                        sprintf(TaxConstants::SUCCESS_MESSAGE_TAX_SET_CREATED, $taxSetTransfer->getIdTaxSet())
                    );
                    $redirectUrl = Url::generate('/tax/set/edit', [
                        static::PARAM_URL_ID_TAX_SET => $taxSetTransfer->getIdTaxSet(),
                    ])->build();

                    return $this->redirectResponse($redirectUrl);
                } catch (DuplicateResourceException $e) {
                    $this->addErrorMessage(TaxConstants::ERROR_MESSAGE_TAX_SET_EXISTS);
                }
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
        $taxSetForm = $this->getFactory()->getTaxSetForm($taxSetFormDataProvider);

        if ($request->request->count() > 0) {
            $taxSetForm->handleRequest($request);

            if ($taxSetForm->isSubmitted() && $taxSetForm->isValid()) {
                $taxSetTransfer = $taxSetForm->getData();
                $taxSetTransfer->setIdTaxSet($idTaxSet);
                try {
                    $rowsAffected = $this->getFacade()->updateTaxSet($taxSetForm->getData());
                    if ($rowsAffected > 0) {
                        $this->addSuccessMessage(sprintf(TaxConstants::SUCCESS_MESSAGE_TAX_SET_UPDATED, $idTaxSet));
                    }
                } catch (DuplicateResourceException $e) {
                    $this->addErrorMessage(TaxConstants::ERROR_MESSAGE_TAX_SET_EXISTS);
                } catch (PropelException $e) {
                }
            } else {
                $this->addErrorMessage(TaxConstants::ERROR_MESSAGE_TAX_SET_UPDATE_ERROR);
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

        try {
            $taxSetTransfer = $this->getFacade()->getTaxSet($idTaxSet);
            $this->getFacade()->deleteTaxSet($idTaxSet);
            $this->addSuccessMessage(sprintf('Tax set %d was deleted successfully.', $idTaxSet));
        } catch (PropelException $e) {
            $this->addErrorMessage('Could not delete tax set. Is it assigned to product or shipping method?');
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
