<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication\Plugin;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesTablePluginInterface;
use Spryker\Zed\SalesReclamation\SalesReclamationConfig;

class SalesTablePlugin implements SalesTablePluginInterface
{
    const URL_CREATE_RECLAMATION = '/sales-reclamation/create';

    /**
     * @param callable $buttonGenerator
     * @param array $item
     *
     * @return array
     */
    public function formatTableRow(callable $buttonGenerator, array $item): array
    {
        $newButton = $this->generateClaimButton($buttonGenerator, $item[SpySalesOrderTableMap::COL_ID_SALES_ORDER]);
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
                SalesReclamationConfig::PARAM_ID_SALES_ORDER => $idSalesOrder,
            ]),
            'Claim',
            [
                'class' => 'btn-remove',
                'icon' => 'fa-repeat',
            ]
        );
    }
}
