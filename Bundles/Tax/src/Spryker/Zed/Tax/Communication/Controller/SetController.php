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
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface getRepository()
 */
class SetController extends AbstractController
{
    public const PARAM_URL_ID_TAX_SET = 'id-tax-set';

    public const REDIRECT_URL_DEFAULT = '/tax/set/list';

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

            if ($taxSetForm->isSubmitted() && $taxSetForm->isValid()) {
                $taxSetTransfer = $this->getFacade()->createTaxSet($taxSetForm->getData());
                $this->addSuccessMessage('Tax set %d was created successfully.', ['%d' => $taxSetTransfer->getIdTaxSet()]);
                $redirectUrl = Url::generate('/tax/set/edit', [
                    static::PARAM_URL_ID_TAX_SET => $taxSetTransfer->getIdTaxSet(),
                ])->build();

                return $this->redirectResponse($redirectUrl);
            }

            $this->addErrorMessage('Tax set is not created. Please fill-in all required fields.');
        }

        return [
          'form' => $taxSetForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function editAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_SET));

        $taxSetFormDataProvider = $this->getFactory()->createTaxSetFormDataProvider();
        $taxSetTransfer = $taxSetFormDataProvider->getData($idTaxSet);

        if ($taxSetTransfer === null) {
            $this->addErrorMessage("Tax set with id %s doesn't exist", ["%s" => $idTaxSet]);

            return $this->redirectResponse(
                static::REDIRECT_URL_DEFAULT
            );
        }

        $taxSetForm = $this->getFactory()->getTaxSetForm($taxSetFormDataProvider, $taxSetTransfer);

        if ($request->request->count() > 0) {
            $taxSetForm->handleRequest($request);

            if ($taxSetForm->isSubmitted() && $taxSetForm->isValid()) {
                $taxSetTransfer = $taxSetForm->getData();
                $taxSetTransfer->setIdTaxSet($idTaxSet);
                $rowsAffected = $this->getFacade()->updateTaxSet($taxSetForm->getData());

                if ($rowsAffected > 0) {
                    $this->addSuccessMessage('Tax set %d was updated successfully.', ['%d' => $idTaxSet]);
                }
            } else {
                $this->addErrorMessage('Tax set is not updated. Please fill-in all required fields.');
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function viewAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->getInt(static::PARAM_URL_ID_TAX_SET));

        $taxSetTransfer = $this->getFacade()->findTaxSet($idTaxSet);

        if ($taxSetTransfer === null) {
            $this->addErrorMessage("Tax set with id %s doesn't exist", ["%s" => $idTaxSet]);

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        return [
            'taxSet' => $taxSetTransfer,
        ];
    }

    /**
     * @deprecated Use DeleteSetController::indexAction() instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idTaxSet = $this->castId($request->query->get(static::PARAM_URL_ID_TAX_SET));
        $url = Url::generate('/tax/delete-set', [
            static::PARAM_URL_ID_TAX_SET => $idTaxSet,
        ])->build();

        return $this->redirectResponse($url, 301);
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
