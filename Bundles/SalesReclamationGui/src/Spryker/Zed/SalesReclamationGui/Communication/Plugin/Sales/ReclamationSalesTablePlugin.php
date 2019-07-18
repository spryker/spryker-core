<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication\Plugin\Sales;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesTablePluginInterface;

class ReclamationSalesTablePlugin implements SalesTablePluginInterface
{
    protected const URL_CREATE_RECLAMATION = '/sales-reclamation-gui/create';

    /**
     * @see \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_ID_SALES_ORDER
     */
    protected const COL_ID_SALES_ORDER = 'spy_sales_order.id_sales_order';

    /**
     * @see \Spryker\Zed\SalesReclamationGui\Communication\Controller\CreateController::COL_ID_SALES_ORDER
     */
    protected const PARAM_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @api
     *
     * @param callable $buttonGenerator
     * @param array $item
     *
     * @return array
     */
    public function formatTableRow(callable $buttonGenerator, array $item): array
    {
        $newButton = $this->generateClaimButton($buttonGenerator, $item[static::COL_ID_SALES_ORDER]);
        $item[static::ROW_ACTIONS] .= ' ' . $newButton;

        return $item;
    }

    /**
     * @param callable $buttonGenerator
     * @param int $idSalesOrder
     *
     * @return string
     */
    protected function generateClaimButton(callable $buttonGenerator, int $idSalesOrder): string
    {
        return $buttonGenerator(
            Url::generate(static::URL_CREATE_RECLAMATION, [
                static::PARAM_ID_SALES_ORDER => $idSalesOrder,
            ]),
            'Claim',
            [
                'class' => 'btn-remove',
                'icon' => 'fa-repeat',
            ]
        );
    }
}
