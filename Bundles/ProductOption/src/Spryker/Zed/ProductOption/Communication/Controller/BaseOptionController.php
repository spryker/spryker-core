<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionRepositoryInterface getRepository()
 */
class BaseOptionController extends AbstractController
{
    public const URL_PARAM_ID_PRODUCT_OPTION_GROUP = 'id-product-option-group';
    public const URL_PARAM_ACTIVE = 'active';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';
    public const URL_PARAM_TABLE_CONTEXT = 'table-context';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productOptionTableAction(Request $request)
    {
        $idProductOptionGroup = $this->castId($request->get(self::URL_PARAM_ID_PRODUCT_OPTION_GROUP));
        $tableContext = $request->get(self::URL_PARAM_TABLE_CONTEXT);

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
        $idProductOptionGroup = $this->castId($request->get(self::URL_PARAM_ID_PRODUCT_OPTION_GROUP));

        $productTable = $this->getFactory()->createProductTable($idProductOptionGroup);

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }
}
