<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Controller;

use DateTime;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SalesController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $idSalesOrder = $request->request->get('id-sales-order');

        return [
            'logs' => [
                [
                    'logType' => 'type',
                    'TransactionId' => uniqid(),
                    'Request' => 'request',
                    'Status' => 'active',
                    'CreatedAt' => new DateTime('now'),
                ],
            ],
        ];
    }

}
