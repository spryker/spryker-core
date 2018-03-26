<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanySupplierGui\Communication\CompanySupplierGuiCommunicationFactory getFactory()
 */
class ProductSupplierController extends AbstractController
{
    protected const PARAM_ID_COMPANY = 'id-company';

    /**
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCompany = $this->castId($request->get(static::PARAM_ID_COMPANY));

        $productSuppliersTable = $this->getFactory()->createProductSuppliersTable($idCompany);

        return [
            'productSuppliersTable' => $productSuppliersTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idCompany = $this->castId($request->get(static::PARAM_ID_COMPANY));

        $productSuppliersTable = $this
            ->getFactory()
            ->createProductSuppliersTable($idCompany);

        return $this->jsonResponse(
            $productSuppliersTable->fetchData()
        );
    }
}
