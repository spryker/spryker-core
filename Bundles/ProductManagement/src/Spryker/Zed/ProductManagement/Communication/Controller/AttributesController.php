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
     * @return array
     */
    public function translateAction(Request $request)
    {
        $idProductManagementAttribute = $this->castId($request->query->get('id'));

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

            // TODO: redirect to "view attribute"
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
        $idProductManagementAttribute = $this->castId($request->query->get('id'));

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

        $attributeValueFormTransfer
            ->fromArray($attributeTranslateFormData, true)
            ->setLocaleName($locale);

        return $attributeValueFormTransfer;
    }

}
