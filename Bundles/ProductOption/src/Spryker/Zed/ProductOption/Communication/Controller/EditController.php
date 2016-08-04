<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Spryker\Shared\Url\Url;
use Spryker\Zed\ProductOption\Communication\Table\ProductOptionTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacade getFacade()
 */
class EditController extends IndexController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductOptionGroup = $this->castId($request->query->get(IndexController::URL_PARAM_ID_PRODUCT_OPTION_GROUP));

        $productOptionGroupTransfer = $this->getFacade()->getProductOptionGroupById($idProductOptionGroup);
        $dataProvider = $this->getFactory()->createGeneralFormDataProvider($productOptionGroupTransfer);

        $productOptionGroupForm = $this->getFactory()->createProductOptionGroup($dataProvider);
        $productOptionGroupForm->handleRequest($request);

        if ($productOptionGroupForm->isValid()) {
            $this->getFacade()->saveProductOptionGroup($productOptionGroupForm->getData());

            $redirectUrl = Url::generate(
                '/product-option/edit/index',
                [
                    IndexController::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $idProductOptionGroup
                ]
            )->build();

            $this->addSuccessMessage('Product option group modifed.');

            return $this->redirectResponse($redirectUrl);
        }

        $productOptionsTable = $this->getFactory()->createProductOptionTable(
            $idProductOptionGroup,
            ProductOptionTable::TABLE_CONTEXT_EDIT
        );

        $productTable = $this->getFactory()->createProductTable($idProductOptionGroup);
        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();

        return [
            'productOptionsTable' => $productOptionsTable->render(),
            'productsTable' => $productTable->render(),
            'productOptionGroup' => $productOptionGroupTransfer,
            'generalForm' => $productOptionGroupForm->createView(),
            'availableLocales' => $availableLocales,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productOptionTableAction(Request $request)
    {
        $idProductOptionGroup = $this->castId($request->get(IndexController::URL_PARAM_ID_PRODUCT_OPTION_GROUP));
        $tableContext = $request->get(IndexController::URL_PARAM_TABLE_CONTEXT);

        $productOptionsTable = $this->getFactory()->createProductOptionTable(
            $idProductOptionGroup,
            $tableContext
        );

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
        $idProductOptionGroup = $this->castId($request->get(IndexController::URL_PARAM_ID_PRODUCT_OPTION_GROUP));

        $productTable = $this->getFactory()->createProductTable($idProductOptionGroup);

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }
}
