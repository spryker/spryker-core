<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Controller;

use Generated\Shared\Transfer\ProductSearchPreferencesTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSearch\Communication\ProductSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface getQueryContainer()
 */
class SearchPreferencesController extends AbstractController
{
    public const PARAM_ID = 'id';
    public const REDIRECT_URL_DEFAULT = '/product-search/search-preferences';

    /**
     * @return array
     */
    public function indexAction()
    {
        $searchPreferencesTable = $this->getFactory()->createSearchPreferencesTable();

        return $this->viewResponse([
            'searchPreferencesTable' => $searchPreferencesTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createSearchPreferencesTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $dataProvider = $this
            ->getFactory()
            ->createSearchPreferencesDataProvider();

        $form = $this->getFactory()
            ->createSearchPreferencesForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productSearchPreferencesTransfer = new ProductSearchPreferencesTransfer();
            $productSearchPreferencesTransfer->fromArray($form->getData(), true);

            $this->getFacade()->createProductSearchPreferences($productSearchPreferencesTransfer);

            $this->addSuccessMessage('Attribute to search was added successfully.');

            return $this->redirectResponse('/product-search/search-preferences');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idAttributeKey = $this->castId($request->query->get(self::PARAM_ID));

        $dataProvider = $this
            ->getFactory()
            ->createSearchPreferencesDataProvider();

        $searchPreferencesFormData = $dataProvider->getData($idAttributeKey);

        if ($searchPreferencesFormData === []) {
            $this->addErrorMessage("Attribute with id %s doesn't exist", ['%s' => $idAttributeKey]);

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        $form = $this->getFactory()
            ->createSearchPreferencesForm(
                $searchPreferencesFormData,
                $dataProvider->getOptions($idAttributeKey)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productSearchPreferencesTransfer = new ProductSearchPreferencesTransfer();
            $productSearchPreferencesTransfer->fromArray($form->getData(), true);

            $this->getFacade()->updateProductSearchPreferences($productSearchPreferencesTransfer);

            $this->addSuccessMessage('Attribute to search was successfully updated.');

            return $this->redirectResponse('/product-search/search-preferences');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cleanAction(Request $request)
    {
        $idAttributeKey = $this->castId($request->query->get(self::PARAM_ID));

        $productSearchPreferencesTransfer = new ProductSearchPreferencesTransfer();
        $productSearchPreferencesTransfer->setIdProductAttributeKey($idAttributeKey);

        $this->getFacade()->cleanProductSearchPreferences($productSearchPreferencesTransfer);

        $this->addSuccessMessage('Attribute to search was successfully deactivated.');

        return $this->redirectResponse('/product-search/search-preferences');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function syncAction()
    {
        $this->getFacade()->touchProductAbstractByAsynchronousAttributeMap();

        $this->addSuccessMessage('Search preferences synchronization was successful.');

        return $this->redirectResponse('/product-search/search-preferences');
    }
}
