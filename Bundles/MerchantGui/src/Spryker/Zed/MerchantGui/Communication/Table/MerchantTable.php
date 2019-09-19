<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Table;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class MerchantTable extends AbstractTable
{
    protected const STATUS_CLASS_MAPPING = [
        'waiting-for-approval' => 'label-info',
        'approved' => 'label-success',
        'active' => 'label-warning',
        'inactive' => 'label-danger',
    ];

    /**
     * @var \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected $merchantQuery;

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantQuery $merchantQuery
     */
    public function __construct(
        SpyMerchantQuery $merchantQuery
    ) {
        $this->merchantQuery = $merchantQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->setSortable([
            MerchantTableConstants::COL_ID_MERCHANT,
            MerchantTableConstants::COL_NAME,
        ]);

        $config->setRawColumns([
            MerchantTableConstants::COL_ACTIONS,
        ]);
        $config->setDefaultSortField(MerchantTableConstants::COL_ID_MERCHANT, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            MerchantTableConstants::COL_ID_MERCHANT,
            MerchantTableConstants::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            MerchantTableConstants::COL_ID_MERCHANT => 'Merchant Id',
            MerchantTableConstants::COL_NAME => 'Name',
        ];

        $actions = [MerchantTableConstants::COL_ACTIONS => 'Actions'];

        $config->setHeader($baseData + $actions);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->merchantQuery, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $rowData = [
                MerchantTableConstants::COL_ID_MERCHANT => $item[SpyMerchantTableMap::COL_ID_MERCHANT],
                MerchantTableConstants::COL_NAME => $item[SpyMerchantTableMap::COL_NAME],
                MerchantTableConstants::COL_ACTIONS => $this->buildLinks($item),
            ];
            $results[] = $rowData;
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];

        $urlParams = [MerchantTableConstants::REQUEST_ID_MERCHANT => $item[MerchantTableConstants::COL_ID_MERCHANT]];
        $buttons[] = $this->generateEditButton(
            Url::generate(MerchantTableConstants::URL_MERCHANT_EDIT, $urlParams),
            'Edit'
        );

        return implode(' ', $buttons);
    }
}
