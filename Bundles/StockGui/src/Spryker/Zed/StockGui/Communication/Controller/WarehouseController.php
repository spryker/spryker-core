<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication\Controller;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Stock\Business\StockFacade;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\StockGui\Communication\StockGuiCommunicationFactory getFactory()
 */
class WarehouseController extends AbstractController
{
    /**
     * @return array
     */
    public function listAction(): array
    {
        $dec = (new StockFacade())->calculateProductAbstractStockForStore(
            '070',
            (new StoreTransfer())->setName('DE')
        );

        $stockTable = $this->getFactory()->createStockTable();

        return $this->viewResponse([
            'stockTable' => $stockTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $stockTable = $this->getFactory()->createStockTable();

        return $this->jsonResponse(
            $stockTable->fetchData()
        );
    }
}
