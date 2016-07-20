<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\ProductManagement\Communication\Form\AttributeTranslationFormCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacade getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class AttributesController extends AbstractController
{

    const PARAM_ID = 'id';
    const PARAM_SEARCH_TEXT = 'q';

    /**
     * @return array
     */
    public function indexAction()
    {
        $attributeTable = $this
            ->getFactory()
            ->createAttributeTable();

        return $this->viewResponse([
            'attributeTable' => $attributeTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $attributeTable = $this
            ->getFactory()
            ->createAttributeTable();

        return $this->jsonResponse(
            $attributeTable->fetchData()
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
            ->createAttributeFormDataProvider();

        $attributeForm = $this
            ->getFactory()
            ->createAttributeForm($dataProvider->getData(), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($attributeForm->isValid()) {
            $attributeTransfer = $this->getFactory()
                ->createAttributeFormTransferGenerator()
                ->createTransfer($attributeForm);

            $attributeTransfer = $this
                ->getFacade()
                ->createProductManagementAttribute($attributeTransfer);

            return $this->redirectResponse(sprintf(
                '/product-management/attributes/translate?id=%d',
                $attributeTransfer->getIdProductManagementAttribute()
            ));
        }

        return $this->viewResponse([
            'form' => $attributeForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function translateAction(Request $request)
    {
        $idProductManagementAttribute = $this->castId($request->query->get(self::PARAM_ID));

        $dataProvider = $this
            ->getFactory()
            ->createAttributeTranslationFormCollectionDataProvider();

        $attributeTranslateFormCollection = $this
            ->getFactory()
            ->createAttributeTranslationFormCollection($dataProvider->getData($idProductManagementAttribute), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($attributeTranslateFormCollection->isValid()) {
            $translationForms = $attributeTranslateFormCollection->get(AttributeTranslationFormCollection::FIELD_TRANSLATIONS);

            $productManagementAttributeTransfer = $this->getFactory()
                ->createAttributeTranslationFormTransferGenerator()
                ->createTransfer($translationForms);

            $this->getFacade()->translateProductManagementAttribute($productManagementAttributeTransfer);

            return $this->redirectResponse(sprintf(
                '/product-management/attributes/view?id=%d',
                $idProductManagementAttribute
            ));
        }

        return $this->viewResponse([
            'form' => $attributeTranslateFormCollection->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function keysAction(Request $request)
    {
        $searchTerm = $request->query->get('term');

        // TODO: get these from spy_product_attribute_key table
        return $this->jsonResponse([
            $searchTerm,
            'Foo',
            'Bar',
            'Baz',
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idProductManagementAttribute = $this->castId($request->query->get(self::PARAM_ID));

        $dataProvider = $this
            ->getFactory()
            ->createAttributeFormDataProvider();

        $attributeForm = $this
            ->getFactory()
            ->createAttributeForm($dataProvider->getData($idProductManagementAttribute), $dataProvider->getOptions($idProductManagementAttribute))
            ->handleRequest($request);

        if ($attributeForm->isValid()) {
            $attributeTransfer = $this->getFactory()
                ->createAttributeFormTransferGenerator()
                ->createTransfer($attributeForm);

            $attributeTransfer = $this
                ->getFacade()
                ->updateProductManagementAttribute($attributeTransfer);

            return $this->redirectResponse(sprintf(
                '/product-management/attributes/translate?id=%d',
                $attributeTransfer->getIdProductManagementAttribute()
            ));
        }

        return $this->viewResponse([
            'form' => $attributeForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idProductManagementAttribute = $this->castId($request->query->get(self::PARAM_ID));

        $generalDataProvider = $this
            ->getFactory()
            ->createAttributeFormDataProvider();
        $attributeForm = $this
            ->getFactory()
            ->createReadOnlyAttributeForm(
                $generalDataProvider->getData($idProductManagementAttribute),
                $generalDataProvider->getOptions($idProductManagementAttribute)
            );

        $translationDataProvider = $this
            ->getFactory()
            ->createAttributeTranslationFormCollectionDataProvider();
        $attributeTranslateFormCollection = $this
            ->getFactory()
            ->createReadOnlyAttributeTranslationFormCollection(
                $translationDataProvider->getData($idProductManagementAttribute),
                $translationDataProvider->getOptions()
            );

        return $this->viewResponse([
            'attributeForm' => $attributeForm->createView(),
            'attributeTranslationFormCollection' => $attributeTranslateFormCollection->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function autocompleteAction(Request $request)
    {
        $idProductManagementAttribute = $this->castId($request->get(self::PARAM_ID));
        $searchText = trim($request->get(self::PARAM_SEARCH_TEXT));
        $idLocale = $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getIdLocale();
        $total = $this->getFacade()->getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale);

        $attributeValueTranslationTransfers = $this->getFacade()
            ->getAttributeValueSuggestions($idProductManagementAttribute, $idLocale, $searchText);

        $values = [];
        foreach ($attributeValueTranslationTransfers as $attributeValueTranslationTransfer) {
            $values[] = $attributeValueTranslationTransfer->toArray();
        }

        return $this->jsonResponse([
            'id_attribute' => $idProductManagementAttribute,
            'values' => $values,
            'total' => $total
        ]);
    }

}
