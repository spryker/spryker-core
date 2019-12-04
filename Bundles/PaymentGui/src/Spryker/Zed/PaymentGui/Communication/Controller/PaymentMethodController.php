<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\PaymentGui\Communication\PaymentGuiCommunicationFactory getFactory()
 */
class PaymentMethodController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $table = $this->getFactory()
            ->createPaymentMethodTable();

        return $this->viewResponse([
            'table' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()->createPaymentMethodTable();

        return $this->jsonResponse($table->fetchData());
    }
}
