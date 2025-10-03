<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Communication\MerchantRegistrationRequestCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Business\MerchantRegistrationRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface getRepository()
 */
class ListController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        $table = $this->getFactory()->createMerchantRegistrationRequestTable();

        return $this->viewResponse([
            'merchantRegistrationRequestTable' => $table->render(),
        ]);
    }

    public function tableDataAction(): JsonResponse
    {
        $table = $this->getFactory()->createMerchantRegistrationRequestTable();

        return $this->jsonResponse($table->fetchData());
    }
}
