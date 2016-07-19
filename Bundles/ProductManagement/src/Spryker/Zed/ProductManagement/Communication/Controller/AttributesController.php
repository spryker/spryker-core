<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTranslationFormTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\ProductManagement\Communication\Form\AttributeForm;
use Spryker\Zed\ProductManagement\Communication\Form\AttributeTranslationForm;
use Spryker\Zed\ProductManagement\Communication\Form\AttributeTranslationFormCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacade getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class AttributesController extends AbstractController
{

    const PARAM_ID = 'id';
    const PARAM_SEARCH_TEXT = 'search_text';

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
            $productManagementAttributeTransfer = $this->createAttributeTransfer($attributeForm);

            $productManagementAttributeTransfer = $this
                ->getFacade()
                ->createProductManagementAttribute($productManagementAttributeTransfer);

            return $this->redirectResponse(sprintf(
                '/product-management/attributes/translate?id=%d',
                $productManagementAttributeTransfer->getIdProductManagementAttribute()
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
            $translationForms = $attributeTranslateFormCollection->get(AttributeTranslationFormCollection::FIELD_TRANSLATIONS)->getData();

            $attributeTranslationFormTransfers = [];
            foreach ($translationForms as $locale => $translationForm) {
                $attributeTranslationFormTransfers[] = $this->createAttributeTranslationFormTransfer($translationForm, $locale);
            }

            $this->getFacade()->translateProductManagementAttribute($attributeTranslationFormTransfers);

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
            $productManagementAttributeTransfer = $this->createAttributeTransfer($attributeForm);

            $productManagementAttributeTransfer = $this
                ->getFacade()
                ->updateProductManagementAttribute($productManagementAttributeTransfer);

            return $this->redirectResponse(sprintf(
                '/product-management/attributes/translate?id=%d',
                $productManagementAttributeTransfer->getIdProductManagementAttribute()
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
     * @param \Symfony\Component\Form\FormInterface $attributeForm
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function createAttributeTransfer(FormInterface $attributeForm)
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->fromArray($attributeForm->getData(), true);

        $values = (array)$attributeForm->get(AttributeForm::FIELD_VALUES)->getData();

        $this->addAttributeValues($productManagementAttributeTransfer, $values);

        return $productManagementAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param array $values
     *
     * @return void
     */
    protected function addAttributeValues(ProductManagementAttributeTransfer $productManagementAttributeTransfer, array $values)
    {
        $productManagementAttributeTransfer->setValues(new \ArrayObject());

        foreach ($values as $value) {
            $productManagementAttributeValueTransfer = new ProductManagementAttributeValueTransfer();
            $productManagementAttributeValueTransfer->setValue($value);

            $productManagementAttributeTransfer->addValue($productManagementAttributeValueTransfer);
        }
    }

    /**
     * @param array $attributeTranslateFormData
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTranslationFormTransfer
     */
    protected function createAttributeTranslationFormTransfer(array $attributeTranslateFormData, $locale)
    {
        $attributeValueFormTransfer = new ProductManagementAttributeTranslationFormTransfer();

        if (!$attributeTranslateFormData[AttributeTranslationForm::FIELD_TRANSLATE_VALUES]) {
            unset($attributeTranslateFormData[AttributeTranslationForm::FIELD_VALUE_TRANSLATIONS]);
        }

        $attributeValueFormTransfer
            ->fromArray($attributeTranslateFormData, true)
            ->setLocaleName($locale);

        return $attributeValueFormTransfer;
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
