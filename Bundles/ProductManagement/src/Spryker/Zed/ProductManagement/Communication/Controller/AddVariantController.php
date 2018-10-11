<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductManagement\ProductManagementConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class AddVariantController extends AbstractController
{
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    protected const PARAM_ID_PRODUCT = 'id-product';

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
            $this->addErrorMessage(sprintf('The product [%s] does not exist.', $idProductAbstract));

            return new RedirectResponse('/product-management');
        }

        $localeProvider = $this->getFactory()->createLocaleProvider();

        $dataProvider = $this->getFactory()->createProductVariantFormAddDataProvider();
        $form = $this
            ->getFactory()
            ->getProductVariantFormAdd(
                $dataProvider->getData(),
                $dataProvider->getOptions($idProductAbstract, ProductManagementConfig::PRODUCT_TYPE_REGULAR)
            )
            ->handleRequest($request);

        $bundledProductTable = $this->getFactory()
            ->createBundledProductTable();

        if ($form->isSubmitted() && $form->isValid()) {
            $productConcreteTransfer = $this->getFactory()
                ->createProductFormTransferGenerator()
                ->buildProductConcreteTransfer($productAbstractTransfer, $form);

            $this->getFactory()
                ->getProductFacade()
                ->saveProduct($productAbstractTransfer, [$productConcreteTransfer]);

            $type = $productConcreteTransfer->getProductBundle() === null ?
                ProductManagementConfig::PRODUCT_TYPE_REGULAR :
                ProductManagementConfig::PRODUCT_TYPE_BUNDLE;

            $this->getFactory()
                ->getProductFacade()
                ->touchProductConcrete($productConcreteTransfer->getIdProductConcrete());

            $this->addSuccessMessage(sprintf(
                'The product [%s] was saved successfully.',
                $productConcreteTransfer->getSku()
            ));

            return $this->createRedirectResponseAfterAdd(
                $idProductAbstract,
                $productConcreteTransfer->getIdProductConcrete(),
                $type
            );
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'productAbstract' => $productAbstractTransfer,
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true),
            'productConcreteFormAddTabs' => $this->getFactory()->createProductConcreteFormAddTabs()->createView(),
            'bundledProductTable' => $bundledProductTable->render(),
            'type' => ProductManagementConfig::PRODUCT_TYPE_REGULAR,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function bundledProductTableAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->get('id-product-concrete'));

        $bundledProductTable = $this->getFactory()
            ->createBundledProductTable($idProductConcrete);

        return $this->jsonResponse(
            $bundledProductTable->fetchData()
        );
    }

    /**
     * @param int $idProductAbstract
     * @param int $idConcreteProduct
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponseAfterAdd(int $idProductAbstract, int $idConcreteProduct, string $type): RedirectResponse
    {
        return $this->redirectResponse(sprintf(
            '/product-management/edit/variant?%s=%d&%s=%d&type=%s',
            static::PARAM_ID_PRODUCT_ABSTRACT,
            $idProductAbstract,
            static::PARAM_ID_PRODUCT,
            $idConcreteProduct,
            $type
        ));
    }
}
