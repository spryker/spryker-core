<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication\Plugin;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Sales\Communication\Table\OrdersTable;
use Spryker\Zed\SalesExtension\Dependency\Plugin\UISalesTablePluginInterface;
use Spryker\Zed\SalesReclamation\SalesReclamationConfig;

class UISalesTablePlugin extends AbstractTable implements UISalesTablePluginInterface
{
    const URL_CREATE_RECLAMATION = '/sales-reclamation/create';

    /**
     * @param array $item
     *
     * @return array
     */
    public function formatQueryLine(array $item): array
    {
        $newButton = $this->generateClaimButton($item[SpySalesOrderTableMap::COL_ID_SALES_ORDER]);
        $item[OrdersTable::URL] .= ' ' . $newButton;

        return $item;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string
     */
    protected function generateClaimButton(int $idSalesOrder): string
    {

        return $this->generateCreateButton(
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

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration|void
     */
    protected function configure(TableConfiguration $config)
    {
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array|void
     */
    protected function prepareData(TableConfiguration $config)
    {
    }
}
