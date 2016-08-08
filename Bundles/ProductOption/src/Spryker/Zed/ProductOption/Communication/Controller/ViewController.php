<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\ProductOption\Communication\Table\ProductOptionTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacade getFacade()
 */
class ViewController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idProductOptionGroup = $this->castId($request->query->get(IndexController::URL_PARAM_ID_PRODUCT_OPTION_GROUP));

        $productOptionsTable = $this->getFactory()->createProductOptionTable(
            $idProductOptionGroup,
            ProductOptionTable::TABLE_CONTEXT_VIEW
        );

        $productOptionGroupTransfer = $this->getFacade()->getProductOptionGroupById($idProductOptionGroup);
        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();
        $taxSetTransfer = $this->getFactory()->getTaxFacade()->getTaxSet($productOptionGroupTransfer->getFkTaxSet());

        return [
            'productOptionGroup' => $productOptionGroupTransfer,
            'availableLocales' => $availableLocales,
            'productOptionsTable' => $productOptionsTable->render(),
            'taxSet' => $taxSetTransfer
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

}
