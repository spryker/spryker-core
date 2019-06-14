<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Controller;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface getRepository()
 */
class RateController extends AbstractController
{
    public const PARAM_URL_ID_TAX_RATE = 'id-tax-rate';

    public const REDIRECT_URL_DEFAULT = '/tax/rate/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->getFactory()->getTaxRateForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taxRateTransfer = $form->getData();

            $rowCount = $this->getQueryContainer()
                ->queryTaxRateWithCountryAndRate(
                    $taxRateTransfer->getName(),
                    $taxRateTransfer->getFkCountry(),
                    $taxRateTransfer->getRate()
                )->count();

            if ($rowCount > 0) {
                $this->addErrorMessage('Tax rate with provided name, percentage and country already exists.');
            } else {
                $taxRateTransfer = $this->getFacade()->createTaxRate($taxRateTransfer);
                if ($taxRateTransfer->getIdTaxRate()) {
                    $this->addSuccessMessage('Tax rate %d was created successfully.', ['%d' => $taxRateTransfer->getIdTaxRate()]);
                    $redirectUrl = Url::generate('/tax/rate/edit', [static::PARAM_URL_ID_TAX_RATE => $taxRateTransfer->getIdTaxRate()])->build();

                    return $this->redirectResponse($redirectUrl);
                }
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function editAction(Request $request)
    {
        $idTaxRate = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_RATE));

        $taxRateFormDataProvider = $this->getFactory()->createTaxRateFormDataProvider();
        $taxRateTransfer = $taxRateFormDataProvider->getData($idTaxRate);

        if ($taxRateTransfer === null) {
            $this->addErrorMessage("Tax rate with id %s doesn't exist", ["%s" => $idTaxRate]);

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        $form = $this->getFactory()->getTaxRateForm($taxRateFormDataProvider, $taxRateTransfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taxRateTransfer = $form->getData();
            $taxRateTransfer->setIdTaxRate($idTaxRate);

            $rowCount = $this->getQueryContainer()
                ->queryTaxRateWithCountryAndRate(
                    $taxRateTransfer->getName(),
                    $taxRateTransfer->getFkCountry(),
                    $taxRateTransfer->getRate()
                )->filterByIdTaxRate($idTaxRate, Criteria::NOT_EQUAL)
                ->count();

            if ($rowCount > 0) {
                $this->addErrorMessage('Tax rate with provided name, percentage and country already exists.');
            } else {
                $rowsAffected = $this->getFacade()->updateTaxRate($taxRateTransfer);
                if ($rowsAffected > 0) {
                    $this->addSuccessMessage('Tax rate %d was updated successfully.', ['%d' => $idTaxRate]);
                }
            }
        }

        return [
            'form' => $form->createView(),
            'taxRate' => $taxRateTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function viewAction(Request $request)
    {
        $idTaxRate = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_RATE));

        $taxRateTransfer = $this->getFacade()->findTaxRate($idTaxRate);

        if ($taxRateTransfer === null) {
            $this->addErrorMessage("Tax rate with id %s doesn't exist", ['%s' => $idTaxRate]);

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        return [
            'taxRate' => $taxRateTransfer,
        ];
    }

    /**
     * @deprecated Use DeleteRateController::indexAction() instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->get(static::PARAM_URL_ID_TAX_RATE));
        $url = Url::generate('/tax/delete-rate', [
            static::PARAM_URL_ID_TAX_RATE => $idTaxSet,
        ])->build();

        return $this->redirectResponse($url, 301);
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
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listTableAction()
    {
        $table = $this->getFactory()->createTaxRateTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }
}
