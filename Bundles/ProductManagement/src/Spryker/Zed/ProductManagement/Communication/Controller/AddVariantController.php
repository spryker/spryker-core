<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductManagement\ProductManagementConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 */
class AddVariantController extends AbstractController
{
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    protected const PARAM_ID_PRODUCT = 'id-product';
    protected const PARAM_TYPE = 'type';
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
            $this->addErrorMessage('The product [%s] does not exist.', ['%s' => $idProductAbstract]);

            return new RedirectResponse('/product-management');
        }

        $localeProvider = $this->getFactory()->createLocaleProvider();

        $dataProvider = $this->getFactory()->createProductVariantFormAddDataProvider();
        $form = $this
            ->getFactory()
            ->getProductVariantFormAdd(
                $dataProvider->getData($request->query->get(static::PARAM_PRICE_DIMENSION)),
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

            $this->addSuccessMessage('The product [%s] was saved successfully.', [
                '%s' => $productConcreteTransfer->getSku(),
            ]);

            return $this->createRedirectResponseAfterAdd($productConcreteTransfer->getIdProductConcrete(), $type, $request);
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
     * @param int $idProduct
     * @param string $type
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponseAfterAdd(int $idProduct, string $type, Request $request): RedirectResponse
    {
        $params = $request->query->all();
        $params[static::PARAM_ID_PRODUCT] = $idProduct;
        $params[static::PARAM_TYPE] = $type;

        return $this->redirectResponse(
            urldecode(Url::generate('/product-management/edit/variant', $params)->build())
        );
    }
}
