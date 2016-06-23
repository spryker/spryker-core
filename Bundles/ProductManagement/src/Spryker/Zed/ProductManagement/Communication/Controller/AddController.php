<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\ProductManagement\Business\Product\MatrixGenerator;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacade getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 */
class AddController extends AbstractController
{

    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createProductFormAddDataProvider();
        $form = $this
            ->getFactory()
            ->createProductFormAdd(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            try {
                $attributeCollection = $this->getFactory()->getProductAttributeCollection();
                $attributes = $this->getAttributesFromData($form->getData(), $attributeCollection);
                $productAbstractTransfer = $this->buildProductAbstractTransferFromData($form->getData());
                $matrixGenerator = new MatrixGenerator();
                $concreteProductCollection = $matrixGenerator->generate($productAbstractTransfer, $attributes);

                $idProductAbstract = $this->getFactory()
                    ->getProductManagementFacade()
                    ->addProduct($productAbstractTransfer, $concreteProductCollection);

                $this->addSuccessMessage('The product was added successfully.');

                return $this->redirectResponse(sprintf(
                    '/product-management/edit?%s=%d' ,
                    self::PARAM_ID_PRODUCT_ABSTRACT,
                    $idProductAbstract
                ));
            } catch (CategoryUrlExistsException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'matrix' => [],
            'concretes' => []
        ]);
    }

    /**
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function buildProductAbstractTransferFromData(array $formData)
    {
        $productAbstractTransfer = $this->createProductAbstractTransfer($formData);

        $attributeData = $formData[ProductFormAdd::LOCALIZED_ATTRIBUTES];
        foreach ($attributeData as $localeCode => $localizedAttributesData) {
            $localeTransfer = $this->getFactory()->getLocaleFacade()->getLocale($localeCode);

            $localizedAttributesTransfer = $this->createLocalizedAttributesTransfer(
                $localizedAttributesData,
                [],
                $localeTransfer
            );

            $productAbstractTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function buildProductConcreteTransferFromData(ProductAbstractTransfer $productAbstractTransfer, array $formData)
    {
        $productConcreteTransfer = new ZedProductConcreteTransfer();
        $productConcreteTransfer->setAttributes([]);
        $productConcreteTransfer->setSku($productAbstractTransfer->getSku() . '-' . rand(1,999));
        $productConcreteTransfer->setIsActive(false);
        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());
        $productConcreteTransfer->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract());

        $attributeData = $formData[ProductFormAdd::LOCALIZED_ATTRIBUTES];
        foreach ($attributeData as $localeCode => $localizedAttributesData) {
            $localeTransfer = $this->getFactory()->getLocaleFacade()->getLocale($localeCode);

            $localizedAttributesTransfer = $this->createLocalizedAttributesTransfer(
                $localizedAttributesData,
                [],
                $localeTransfer
            );

            $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(array $data)
    {
        $productAbstractTransfer = new ProductAbstractTransfer();

        $productAbstractTransfer->setSku(
            $this->slugify($data[ProductFormAdd::FIELD_SKU])
        );

        return $productAbstractTransfer;
    }

    /**
     * @param array $data
     * @param array $abstractLocalizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createLocalizedAttributesTransfer(array $data, array $abstractLocalizedAttributes, LocaleTransfer $localeTransfer)
    {
        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setLocale($localeTransfer);
        $localizedAttributesTransfer->setName($data[ProductFormAdd::FIELD_NAME]);
        $localizedAttributesTransfer->setAttributes($abstractLocalizedAttributes);

        return $localizedAttributesTransfer;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function slugify($value)
    {
        if (function_exists('iconv')) {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }

        $value = preg_replace("/[^a-zA-Z0-9 -]/", "", trim($value));
        $value = strtolower($value);
        $value = str_replace(' ', '-', $value);

        return $value;
    }

    /**
     * @param array $data
     * @param array $attributeCollection
     *
     * @return array
     */
    protected function getAttributesFromData(array $data, array $attributeCollection)
    {
        $attributes = [];
        foreach ($data[ProductFormAdd::ATTRIBUTES] as $type => $values) {
            $values = $this->getAttributeValues($values['value'], $attributeCollection[$type]);
            $attributes[$type] = $values;
        }

        return $attributes;
    }

    /**
     * @param array $keyCollection
     * @param array $attributes
     *
     * @return array
     */
    protected function getAttributeValues(array $keyCollection, array $attributes)
    {
        $values = [];
        foreach ($keyCollection as $key) {
            $values[$key] =  $attributes[$key];
        }

        return $values;
    }

}
