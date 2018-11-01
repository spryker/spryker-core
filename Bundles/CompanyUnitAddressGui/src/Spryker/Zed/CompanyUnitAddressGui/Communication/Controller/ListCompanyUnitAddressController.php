<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CompanyUnitAddressGui\Communication\CompanyUnitAddressGuiCommunicationFactory getFactory()
 */
class ListCompanyUnitAddressController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $addressTable = $this->getFactory()
            ->createAddressTable();

        return $this->viewResponse([
            'addresses' => $addressTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $addressTable = $this->getFactory()
            ->createAddressTable();

        return $this->jsonResponse($addressTable->fetchData());
    }
}
