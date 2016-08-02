<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Spryker\Shared\Url\Url;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacade getFacade()
 */
class IndexController extends AbstractController
{
    const URL_PARAM_ID_PRODUCT_OPTION_GROUP = 'id-product-option-group';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createGeneralFormDataProvider();

        $productOptionGroupForm = $this->getFactory()->createProductOptionGroup($dataProvider);
        $productOptionGroupForm->handleRequest($request);

        if ($productOptionGroupForm->isValid()) {
            $productOptionGroupTransfer = $productOptionGroupForm->getData();
            $idProductOptionGroup = $this->getFacade()->saveProductOptionGroup($productOptionGroupTransfer);

            $redirectUrl = Url::generate(
                '/product-option/index/edit',
                [
                    self::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $idProductOptionGroup
                ]
            )->build();

            return $this->redirectResponse($redirectUrl);
        }

        $productTable = $this->getFactory()->createProductTable();

        return [
            'generalForm' => $productOptionGroupForm->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'productsTable' => $productTable->render(),
        ];
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idProductOptionGroup = $this->castId($request->query->get(self::URL_PARAM_ID_PRODUCT_OPTION_GROUP));

        $productOptionGroupTransfer = $this->getFacade()->getProductOptionGroupById($idProductOptionGroup);
        $dataProvider = $this->getFactory()->createGeneralFormDataProvider($productOptionGroupTransfer);

        $productOptionGroupForm = $this->getFactory()->createProductOptionGroup($dataProvider);
        $productOptionGroupForm->handleRequest($request);

        if ($productOptionGroupForm->isValid()) {
            $this->getFacade()->saveProductOptionGroup($productOptionGroupForm->getData());

            $redirectUrl = Url::generate(
                '/product-option/index/edit',
                [
                    self::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $idProductOptionGroup
                ]
            )->build();

            return $this->redirectResponse($redirectUrl);
        }

        $productOptionsTable = $this->getFactory()->createProductOptionTable($idProductOptionGroup);
        $productTable = $this->getFactory()->createProductTable($idProductOptionGroup);

        return [
            'productOptionTable' => $productOptionsTable->render(),
            'productsTable' => $productTable->render(),
            'generalForm' => $productOptionGroupForm->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productOptionTableAction(Request $request)
    {
        $idProductOptionGroup = $this->castId($request->get(self::URL_PARAM_ID_PRODUCT_OPTION_GROUP));

        $productOptionsTable = $this->getFactory()->createProductOptionTable($idProductOptionGroup);

        return $this->jsonResponse(
            $productOptionsTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productTableAction(Request $request)
    {
        $idProductOptionGroup = $this->castId($request->get(self::URL_PARAM_ID_PRODUCT_OPTION_GROUP));

        $productTable = $this->getFactory()->createProductTable($idProductOptionGroup);

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }

}
