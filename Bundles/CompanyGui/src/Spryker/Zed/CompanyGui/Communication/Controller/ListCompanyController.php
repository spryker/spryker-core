<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CompanyGui\Communication\CompanyGuiCommunicationFactory getFactory()
 */
class ListCompanyController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $companyTable = $this->getFactory()
            ->createCompanyTable();

        return $this->viewResponse([
            'companies' => $companyTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCompanyTable();

        return $this->jsonResponse($table->fetchData());
    }
}
