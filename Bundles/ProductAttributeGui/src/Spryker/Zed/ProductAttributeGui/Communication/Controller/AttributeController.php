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
    public const PARAM_ID = 'id';
    public const PARAM_SEARCH_TEXT = 'q';
    public const PARAM_TERM = 'term';
    public const PARAM_LOCALE_CODE = 'locale_code';

    public const MESSAGE_ATTRIBUTE_CREATE_SUCCESS = 'Product attribute was created successfully.';
    public const MESSAGE_ATTRIBUTE_CREATE_ERROR = 'Product attribute was not created.';
    public const MESSAGE_ATTRIBUTE_UPDATE_SUCCESS = 'Product attribute was updated successfully.';
    public const MESSAGE_TRANSLATION_UPDATE_SUCCESS = 'Translation was updated successfully.';

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
            ->getAttributeForm($dataProvider->getData(), $dataProvider->getOptions())
            ->handleRequest($request);

        if (!$attributeForm->isSubmitted() || !$attributeForm->isValid()) {
            return $this->viewResponse([
                'form' => $attributeForm->createView(),
            ]);
        }

        $attributeTransfer = $this->getFactory()
            ->createAttributeFormTransferGenerator()
            ->createTransfer($attributeForm);

        $attributeTransfer = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->createProductManagementAttribute($attributeTransfer);

        if (!$attributeTransfer->getIdProductManagementAttribute()) {
            $this->addErrorMessage(static::MESSAGE_ATTRIBUTE_CREATE_ERROR);

            return $this->redirectResponse(sprintf(
                '/product-attribute-gui/attribute/translate?id=%d',
                $attributeTransfer->getIdProductManagementAttribute()
            ));
        }

        $this->addSuccessMessage(static::MESSAGE_ATTRIBUTE_CREATE_SUCCESS);

        return $this->redirectResponse(sprintf(
            '/product-attribute-gui/attribute/translate?id=%d',
            $attributeTransfer->getIdProductManagementAttribute()
        ));
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
            ->getAttributeTranslationFormCollection($dataProvider->getData($idProductManagementAttribute), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($attributeTranslateFormCollection->isSubmitted() === false || $attributeTranslateFormCollection->isValid() === false) {
            return $this->viewResponse([
                'form' => $attributeTranslateFormCollection->createView(),
                'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            ]);
        }

        $translationForms = $attributeTranslateFormCollection->get(AttributeTranslationCollectionForm::FIELD_TRANSLATIONS);

        $productManagementAttributeTransfer = $this->getFactory()
            ->createAttributeTranslationFormTransferGenerator()
            ->createTransfer($translationForms);

        $this->getFactory()
            ->getProductAttributeFacade()
            ->translateProductManagementAttribute($productManagementAttributeTransfer);

        $this->addSuccessMessage(static::MESSAGE_TRANSLATION_UPDATE_SUCCESS);

        return $this->redirectResponse(sprintf(
            '/product-attribute-gui/attribute/view?id=%d',
            $idProductManagementAttribute
        ));
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
            ->getAttributeForm($dataProvider->getData($idProductManagementAttribute), $dataProvider->getOptions($idProductManagementAttribute))
            ->handleRequest($request);

        if (!$attributeForm->isSubmitted() || !$attributeForm->isValid()) {
            return $this->viewResponse([
                'form' => $attributeForm->createView(),
            ]);
        }

        $attributeTransfer = $this->getFactory()
            ->createAttributeFormTransferGenerator()
            ->createTransfer($attributeForm);

        $attributeTransfer = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->updateProductManagementAttribute($attributeTransfer);

        $this->addSuccessMessage(static::MESSAGE_ATTRIBUTE_UPDATE_SUCCESS);

        return $this->redirectResponse(sprintf(
            '/product-attribute-gui/attribute/translate?id=%d',
            $attributeTransfer->getIdProductManagementAttribute()
        ));
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

        $localeTransfer = $this->getCurrentLocaleTransfer($localeCode);
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

    /**
     * @param string|null $localeCode
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocaleTransfer($localeCode)
    {
        $hasLocale = $this->getFactory()
            ->getLocaleFacade()
            ->hasLocale($localeCode);

        if ($hasLocale) {
            return $this->getFactory()
                ->getLocaleFacade()
                ->getLocale($localeCode);
        }

        return $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();
    }
}
