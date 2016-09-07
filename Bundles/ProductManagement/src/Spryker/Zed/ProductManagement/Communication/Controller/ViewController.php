<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacade getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 */
class ViewController extends AddController
{

    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    const PARAM_ID_PRODUCT = 'id-product';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->get(
            self::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $productAbstractTransfer = $this->getFactory()
            ->getProductManagementFacade()
            ->getProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            $this->addErrorMessage(sprintf('The product [%s] you are trying to edit, does not exist.', $idProductAbstract));

            return new RedirectResponse('/product-management');
        }

        $concreteProductCollection = $this->getFactory()
            ->getProductManagementFacade()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);

        $attributeCollection = $this->normalizeAttributeArray(
            $this->getFactory()->getProductAttributeCollection()
        );

        $localeProvider = $this->getFactory()->createLocaleProvider();

        $variantTable = $this
            ->getFactory()
            ->createVariantTable($idProductAbstract);

        s($productAbstractTransfer->toArray(true));

        return $this->viewResponse([
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'currentProduct' => $productAbstractTransfer->toArray(),
            'concreteProductCollection' => $concreteProductCollection,
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true),
            'variantTable' => $variantTable->render(),
            'idProduct' => null,
            'idProductAbstract' => $idProductAbstract,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function variantAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->get(
            self::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $idProduct = $this->castId($request->get(
            self::PARAM_ID_PRODUCT
        ));

        $productTransfer = $this->getFactory()
            ->getProductManagementFacade()
            ->getProductConcreteById($idProduct);

        if (!$productTransfer) {
            $this->addErrorMessage(sprintf('The product [%s] you are trying to edit, does not exist.', $idProduct));

            return new RedirectResponse('/product-management/edit?id-product-abstract=' . $idProductAbstract);
        }

        $localeProvider = $this->getFactory()->createLocaleProvider();

        $dataProvider = $this->getFactory()->createProductVariantFormEditDataProvider();
        $form = $this
            ->getFactory()
            ->createProductVariantFormEdit(
                $dataProvider->getData($idProductAbstract, $idProduct),
                $dataProvider->getOptions($idProductAbstract)
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            try {
                $productAbstractTransfer = $this->getFactory()
                    ->getProductManagementFacade()
                    ->getProductAbstractById($idProductAbstract);

                $productConcreteTransfer = $this->getFactory()
                    ->createProductFormTransferGenerator()
                    ->buildProductConcreteTransfer($productAbstractTransfer, $form, $idProduct);

                $idProduct = $this->getFactory()
                    ->getProductManagementFacade()
                    ->saveProduct($productAbstractTransfer, [$productConcreteTransfer]);

                $this->addSuccessMessage(sprintf(
                    'The product [%s] was saved successfully.',
                    $productConcreteTransfer->getSku()
                ));

                return $this->redirectResponse(sprintf(
                    '/product-management/edit/variant?%s=%d&%s=%d',
                    self::PARAM_ID_PRODUCT_ABSTRACT,
                    $idProductAbstract,
                    self::PARAM_ID_PRODUCT,
                    $idProduct
                ));
            } catch (CategoryUrlExistsException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        };

        return $this->viewResponse([
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'currentProduct' => $productTransfer->toArray(),
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true),
            'idProduct' => $idProduct,
            'idProductAbstract' => $idProductAbstract,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function variantTableAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->get(
            self::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $variantTable = $this
            ->getFactory()
            ->createVariantTable($idProductAbstract);

        return $this->jsonResponse(
            $variantTable->fetchData()
        );
    }

}
