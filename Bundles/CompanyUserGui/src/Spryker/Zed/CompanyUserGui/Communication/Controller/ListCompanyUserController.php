<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class ListCompanyUserController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $companyUserTable = $this->getFactory()
            ->createCompanyUserTable();

        return $this->viewResponse([
            'companyUserTable' => $companyUserTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $companyUserTable = $this->getFactory()
            ->createCompanyUserTable();

        return $this->jsonResponse($companyUserTable->fetchData());
    }
}
