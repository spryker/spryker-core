<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountMerchantSalesOrderGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\DiscountMerchantSalesOrderGui\Communication\DiscountMerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountMerchantSalesOrderGui\Business\DiscountMerchantSalesOrderGuiFacadeInterface getFacade()
 */
class MerchantSalesOrderController extends AbstractController
{
    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request): array
    {
        /**
         * @var \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
         */
        $merchantOrderTransfer = $request->request->get('merchantOrderTransfer');

        return [
            'merchantOrder' => $merchantOrderTransfer,
        ];
    }
}
