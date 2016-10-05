<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
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

        $attributeCollection = $this->normalizeAttributeArray(
            $this->getFactory()->getProductAttributeCollection()
        );

        $localeProvider = $this->getFactory()->createLocaleProvider();

        if ($form->isValid()) {
            try {
                $productAbstractTransfer = $this->getFactory()
                    ->createProductFormTransferGenerator()
                    ->buildProductAbstractTransfer($form);

                $attributeValues = $this->getFactory()
                    ->createProductFormTransferGenerator()
                    ->generateVariantAttributeArrayFromData($form->getData(), $attributeCollection);

                $concreteProductCollection = $this->getFactory()
                    ->createProductVariantGenerator()
                    ->generate($productAbstractTransfer, $attributeValues);

                $idProductAbstract = $this->getFactory()
                    ->getProductFacade()
                    ->addProduct($productAbstractTransfer, $concreteProductCollection);

                $this->addSuccessMessage(sprintf(
                    'The product [%s] was added successfully.',
                    $productAbstractTransfer->getSku()
                ));

                return $this->redirectResponse(sprintf(
                    '/product-management/edit?%s=%d',
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
            'concreteProductCollection' => [],
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true),
            'productFormAddTabs' => $this->getFactory()->createProductFormAddTabs()->createView(),
        ]);
    }

    /**
     * @param array $keys
     * @param array $attributes
     *
     * @return array
     */
    protected function getAttributeValues(array $keys, array $attributes)
    {
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $attributes[$key];
        }

        return $values;
    }

    /**
     * @param array $attributeCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected function normalizeAttributeArray(array $attributeCollection)
    {
        $attributeArray = [];
        foreach ($attributeCollection as $attributeTransfer) {
            $attributeArray[$attributeTransfer->getKey()] = $attributeTransfer;
        }

        return $attributeArray;
    }

}
