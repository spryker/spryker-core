<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 */
class ListCompanyBusinessUnitController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $companyBusinessUnitTable = $this->getFactory()
            ->createCompanyBusinessUnitTable();

        return $this->viewResponse([
            'companyBusinessUnitTable' => $companyBusinessUnitTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createCompanyBusinessUnitTable();

        return $this->jsonResponse($table->fetchData());
    }
}
