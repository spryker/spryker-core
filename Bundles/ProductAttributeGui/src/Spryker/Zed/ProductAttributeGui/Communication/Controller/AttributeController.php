<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeTranslationCollectionForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class AttributeController extends AbstractController
{

    const PARAM_ID = 'id';
    const PARAM_SEARCH_TEXT = 'q';
    const PARAM_TERM = 'term';
    const PARAM_LOCALE_CODE = 'locale_code';

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
                ->getFactory()
                ->getProductAttributeFacade()
                ->createProductManagementAttribute($attributeTransfer);

            return $this->redirectResponse(sprintf(
                '/product-attribute-gui/attribute/translate?id=%d',
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
        $idProductManagementAttribute = $this->castId($request->query->get(static::PARAM_ID));

        $dataProvider = $this
            ->getFactory()
            ->createAttributeTranslationFormCollectionDataProvider();

        $attributeTranslateFormCollection = $this
            ->getFactory()
            ->createAttributeTranslationFormCollection($dataProvider->getData($idProductManagementAttribute), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($attributeTranslateFormCollection->isValid()) {
            $translationForms = $attributeTranslateFormCollection->get(AttributeTranslationCollectionForm::FIELD_TRANSLATIONS);

            $productManagementAttributeTransfer = $this->getFactory()
                ->createAttributeTranslationFormTransferGenerator()
                ->createTransfer($translationForms);

            $this->getFactory()
                ->getProductAttributeFacade()
                ->translateProductManagementAttribute($productManagementAttributeTransfer);

            return $this->redirectResponse(sprintf(
                '/product-attribute-gui/attribute/view?id=%d',
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
        $searchTerm = $request->query->get(static::PARAM_TERM);

        $keys = $this->getFactory()
            ->getProductAttributeFacade()
            ->suggestUnusedAttributeKeys($searchTerm);

        return $this->jsonResponse($keys);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idProductManagementAttribute = $this->castId($request->query->get(static::PARAM_ID));

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
                ->getFactory()
                ->getProductAttributeFacade()
                ->updateProductManagementAttribute($attributeTransfer);

            return $this->redirectResponse(sprintf(
                '/product-attribute-gui/attribute/translate?id=%d',
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
    public function viewAction(Request $request)
    {
        $idProductManagementAttribute = $this->castId($request->query->get(static::PARAM_ID));

        $attributeTransfer = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->getProductManagementAttribute($idProductManagementAttribute);

        if (!$attributeTransfer) {
            return $this->redirectResponse('/product-attribute-gui/attribute');
        }

        return $this->viewResponse([
            'attributeTransfer' => $attributeTransfer,
            'locales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function suggestAction(Request $request)
    {
        $idProductManagementAttribute = $this->castId($request->get(static::PARAM_ID));
        $localeCode = $request->get(static::PARAM_LOCALE_CODE);
        $searchText = trim($request->get(static::PARAM_SEARCH_TEXT));

        try {
            $localeTransfer = $this->getFactory()
                ->getLocaleFacade()
                ->getLocale($localeCode);
        } catch (\Exception $e) {
            $localeTransfer = $this->getFactory()
                ->getLocaleFacade()
                ->getCurrentLocale();
        }

        $idLocale = $localeTransfer->getIdLocale();

        $total = $this->getFactory()
            ->getProductAttributeFacade()
            ->getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale);

        $values = $this->getFactory()
            ->getProductAttributeFacade()
            ->getAttributeValueSuggestions($idProductManagementAttribute, $idLocale, $searchText);

        return $this->jsonResponse([
            'id_attribute' => $idProductManagementAttribute,
            'values' => $values,
            'total' => $total,
        ]);
    }

}
