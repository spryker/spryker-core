<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CompanySupplierGui\Communication\CompanySupplierGuiCommunicationFactory getFactory()
 */
class ProductSupplierController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $productSuppliersTable = $this->getFactory()->createProductSuppliersTable();

        return [
            'productSuppliersTable' => $productSuppliersTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $productSuppliersTable = $this
            ->getFactory()
            ->createProductSuppliersTable();

        return $this->jsonResponse(
            $productSuppliersTable->fetchData()
        );
    }
}
