<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Table;

use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class MerchantRelationshipMinimumOrderValueTable extends AbstractTable
{
    protected const COL_ID_MERCHANT_RELATIONSHIP = SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP;

    protected const COL_COMPANY_RELATION = 'Company name';

    protected const COL_ACTIONS = 'Actions';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config = $this->setHeader($config);

        $config->addRawColumn(static::COL_ACTIONS);
        $config->addRawColumn(static::COL_COMPANY_RELATION);

        $config->setSortable([
            static::COL_ID_MERCHANT_RELATIONSHIP,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->prepareQuery();
        $queryResults = $this->runQuery($query, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = $this->prepareRowData($item);
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function prepareQuery(): SpyMerchantRelationshipQuery
    {
        $query = SpyMerchantRelationshipQuery::create();

        return $query;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            static::COL_ID_MERCHANT_RELATIONSHIP => 'Company Unit Address Id',
            static::COL_COMPANY_RELATION => 'Company',
        ];

        $actions = [static::COL_ACTIONS => static::COL_ACTIONS];

        $config->setHeader($baseData + $actions);

        return $config;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function prepareRowData(array $item): array
    {
        $baseData = [
            static::COL_ID_MERCHANT_RELATIONSHIP => $item[static::COL_ID_MERCHANT_RELATIONSHIP],
            static::COL_COMPANY_RELATION => $this->getCompanyName(),
        ];

        $actions = [static::COL_ACTIONS => $this->buildLinks($item)];

        return $baseData + $actions;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item)
    {
        $buttons = [];

        return implode(' ', $buttons);
    }

    /**
     * @return string
     */
    protected function getCompanyName(): string
    {
        return '';
    }
}
