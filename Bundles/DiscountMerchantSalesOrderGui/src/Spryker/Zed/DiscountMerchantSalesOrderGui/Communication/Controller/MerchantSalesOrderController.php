<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountMerchantSalesOrderGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\DiscountMerchantSalesOrderGui\Communication\DiscountMerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountMerchantSalesOrderGui\Business\DiscountMerchantSalesOrderGuiFacadeInterface getFacade()
 */
class MerchantSalesOrderController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function listAction(Request $request): array
    {
        $merchantOrderTransfer = $this->getMerchantOrderTransfer($request);

        return [
            'merchantOrder' => $merchantOrderTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function getMerchantOrderTransfer(Request $request): MerchantOrderTransfer
    {
        // @deprecated Exists for BC reasons. Will be removed in the next major release.
        if ($request->request->has('merchantOrderTransfer')) {
            /** @phpstan-var \Generated\Shared\Transfer\MerchantOrderTransfer */
            return $request->request->get('merchantOrderTransfer');
        }

        if (!$request->request->has('serializedMerchantOrderTransfer')) {
            throw new InvalidArgumentException('`serializedMerchantOrderTransfer` not found in request');
        }

        $merchantOrderTransfer = new MerchantOrderTransfer();
        $merchantOrderTransfer->unserialize((string)$request->request->get('serializedMerchantOrderTransfer'));

        return $merchantOrderTransfer;
    }
}
