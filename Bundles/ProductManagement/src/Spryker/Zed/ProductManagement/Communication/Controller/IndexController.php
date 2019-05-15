<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    public const ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @return array
     */
    public function indexAction()
    {
        $productTable = $this
            ->getFactory()
            ->createProductTable();

        return $this->viewResponse([
            'productTable' => $productTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $productTable = $this
            ->getFactory()
            ->createProductTable();

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }
}
