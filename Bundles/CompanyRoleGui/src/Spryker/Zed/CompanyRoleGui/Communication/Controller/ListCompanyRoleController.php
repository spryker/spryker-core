<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class ListCompanyRoleController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()
            ->createCompanyRoleTable();

        return $this->viewResponse([
            'companyRoleTable' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCompanyRoleTable();

        return $this->jsonResponse($table->fetchData());
    }
}
