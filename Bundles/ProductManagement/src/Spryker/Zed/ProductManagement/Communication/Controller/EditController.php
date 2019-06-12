<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\ProductManagement\ProductManagementConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class EditController extends AddController
{
    public const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    public const PARAM_ID_PRODUCT = 'id-product';
    public const PARAM_PRODUCT_TYPE = 'type';
    protected const PARAM_PRICE_DIMENSION = 'price-dimension';

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
            ->getProductFacade()
            ->findProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            $this->addErrorMessage('The product [%s] you are trying to edit, does not exist.', [
                '%s' => $idProductAbstract,
            ]);

            return new RedirectResponse('/product-management');
        }

        $dataProvider = $this->getFactory()->createProductFormEditDataProvider();
        $form = $this
            ->getFactory()
            ->createProductFormEdit(
                $dataProvider->getData($idProductAbstract, $request->query->get(static::PARAM_PRICE_DIMENSION)),
                $dataProvider->getOptions($idProductAbstract)
            )
            ->handleRequest($request);

        $concreteProductCollection = $this->getFactory()
            ->getProductFacade()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);

        $localeProvider = $this->getFactory()->createLocaleProvider();

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $productAbstractTransfer = $this->getFactory()
                    ->createProductFormTransferGenerator()
                    ->buildProductAbstractTransfer($form, $idProductAbstract);

                $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

                $idProductAbstract = $this->getFactory()
                    ->getProductFacade()
                    ->saveProduct($productAbstractTransfer, []);

                $this->getFactory()
                    ->getProductFacade()
                    ->touchProductAbstract($idProductAbstract);

                $this->addSuccessMessage('The product [%s] was saved successfully.', ['%s' => $productAbstractTransfer->getSku()]);

                return $this->redirectResponse(
                    urldecode(Url::generate('/product-management/edit', $request->query->all())->build())
                );
            } catch (CategoryUrlExistsException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        $type = $request->query->get(self::PARAM_PRODUCT_TYPE);

        $variantTable = $this
            ->getFactory()
            ->createVariantTable($idProductAbstract, $type);

        $viewData = [
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'currentProduct' => $productAbstractTransfer->toArray(),
            'superAttributesCount' => $this->getFactory()->createProductAttributeHelper()->getProductAbstractSuperAttributesCount($productAbstractTransfer),
            'concreteProductCollection' => $concreteProductCollection,
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true),
            'variantTable' => $variantTable->render(),
            'idProduct' => null,
            'idProductAbstract' => $idProductAbstract,
            'priceDimension' => $request->get(static::PARAM_PRICE_DIMENSION, []),
            'productFormEditTabs' => $this->getFactory()->createProductFormEditTabs()->createView(),
        ];

        $viewData = $this->getFactory()
            ->createAbstractProductEditViewExpanderPluginExecutor()
            ->expandEditAbstractProductViewData($viewData);

        return $this->viewResponse($viewData);
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
            ->getProductFacade()
            ->findProductConcreteById($idProduct);

        if (!$productTransfer) {
            $this->addErrorMessage('The product [%s] you are trying to edit, does not exist.', ['%s' => $idProduct]);

            return new RedirectResponse('/product-management/edit?id-product-abstract=' . $idProductAbstract);
        }

        $type = ProductManagementConfig::PRODUCT_TYPE_REGULAR;
        if ($productTransfer->getProductBundle() !== null) {
            $type = ProductManagementConfig::PRODUCT_TYPE_BUNDLE;
        }

        $localeProvider = $this->getFactory()->createLocaleProvider();

        $dataProvider = $this->getFactory()->createProductVariantFormEditDataProvider();
        $form = $this
            ->getFactory()
            ->createProductVariantFormEdit(
                $dataProvider->getData($idProductAbstract, $idProduct, $request->query->get(static::PARAM_PRICE_DIMENSION)),
                $dataProvider->getOptions($idProductAbstract, $type)
            )
            ->handleRequest($request);

        $bundledProductTable = $this->getFactory()
            ->createBundledProductTable($idProduct);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $productAbstractTransfer = $this->getFactory()
                    ->getProductFacade()
                    ->findProductAbstractById($idProductAbstract);

                $productConcreteTransfer = $this->getFactory()
                    ->createProductFormTransferGenerator()
                    ->buildProductConcreteTransfer($productAbstractTransfer, $form, $idProduct);

                $productConcreteTransfer = $this->getFactory()
                    ->getProductBundleFacade()
                    ->saveBundledProducts($productConcreteTransfer);

                $this->getFactory()
                    ->getProductFacade()
                    ->saveProduct($productAbstractTransfer, [$productConcreteTransfer]);

                $this->getFactory()
                    ->getProductFacade()
                    ->touchProductConcrete($idProduct);

                $this->addSuccessMessage('The product [%s] was saved successfully.', [
                    '%s' => $productConcreteTransfer->getSku(),
                ]);

                return $this->redirectResponse(
                    urldecode(Url::generate('/product-management/edit/variant', $request->query->all())->build())
                );
            } catch (CategoryUrlExistsException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        $viewData = [
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'currentProduct' => $productTransfer->toArray(),
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true),
            'idProduct' => $idProduct,
            'idProductAbstract' => $idProductAbstract,
            'priceDimension' => $request->get(static::PARAM_PRICE_DIMENSION, []),
            'productConcreteFormEditTabs' => $this->getFactory()->createProductConcreteFormEditTabs()->createView(),
            'bundledProductTable' => $bundledProductTable->render(),
            'type' => $type,
        ];

        $viewData = $this->getFactory()
            ->createProductConcreteEditViewExpanderPluginExecutor()
            ->expandEditVariantViewData($viewData);

        return $this->viewResponse($viewData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function variantTableAction(Request $request)
    {
        $idProductAbstract = $this->castId(
            $request->get(self::PARAM_ID_PRODUCT_ABSTRACT)
        );

        $type = $request->get(self::PARAM_PRODUCT_TYPE);

        $variantTable = $this
            ->getFactory()
            ->createVariantTable($idProductAbstract, $type);

        return $this->jsonResponse(
            $variantTable->fetchData()
        );
    }
}
