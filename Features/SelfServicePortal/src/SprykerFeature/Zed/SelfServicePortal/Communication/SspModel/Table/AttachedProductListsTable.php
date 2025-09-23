<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Table;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelToProductListQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class AttachedProductListsTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const COL_ID = 'id_product_list';

    /**
     * @var string
     */
    protected const COL_TITLE = 'title';

    /**
     * @var \Orm\Zed\SelfServicePortal\Persistence\SpySspModelToProductListQuery
     */
    protected SpySspModelToProductListQuery $sspModelToProductListQuery;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig
     */
    protected SelfServicePortalConfig $selfServicePortalConfig;

    /**
     * @var int
     */
    protected int $idSspModel;

    public function __construct(
        SpySspModelToProductListQuery $sspModelToProductListQuery,
        SelfServicePortalConfig $selfServicePortalConfig,
        int $idSspModel
    ) {
        $this->sspModelToProductListQuery = $sspModelToProductListQuery;
        $this->selfServicePortalConfig = $selfServicePortalConfig;
        $this->idSspModel = $idSspModel;
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $url = Url::generate(
            'attached-product-lists-table',
            ['id-ssp-model' => $this->idSspModel],
        );

        $config->setUrl($url->build());

        $config->setHeader([
            static::COL_ID => 'ID',
            static::COL_TITLE => 'Title',
        ]);

        $config->setSortable([
            static::COL_ID,
            static::COL_TITLE,
        ]);

        $config->setSearchable([
            static::COL_ID,
            static::COL_TITLE,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->sspModelToProductListQuery
            ->filterByFkSspModel($this->idSspModel)
            ->joinSpyProductList()
            ->withColumn(SpyProductListTableMap::COL_ID_PRODUCT_LIST, static::COL_ID)
            ->withColumn(SpyProductListTableMap::COL_TITLE, static::COL_TITLE);

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->formatRow($item);
        }

        return $results;
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    protected function formatRow(array $item): array
    {
        return [
            static::COL_ID => $item[static::COL_ID] ?? '',
            static::COL_TITLE => $item[static::COL_TITLE] ?? '',
        ];
    }
}
