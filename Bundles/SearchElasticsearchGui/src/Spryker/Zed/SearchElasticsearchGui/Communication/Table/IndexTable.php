<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Communication\Table;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeInterface;

class IndexTable extends AbstractTable
{
    protected const COL_INDEX = 'COL_INDEX';

    /**
     * @var \Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeInterface
     */
    protected $searchElasticsearchFacade;

    /**
     * @param \Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeInterface $searchElasticsearchFacade
     */
    public function __construct(SearchElasticsearchGuiToSearchElasticsearchFacadeInterface $searchElasticsearchFacade)
    {
        $this->searchElasticsearchFacade = $searchElasticsearchFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->disableSearch();

        $headers = [
            static::COL_INDEX => 'Index',
        ];

        $config->setHeader($headers);
        $config->setRawColumns([
            static::COL_INDEX,
        ]);
        $config->setUrl('list-indexes-ajax');
        $config->setDefaultSortField(static::COL_INDEX);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $tableData = [];
        $indexNames = $this->searchElasticsearchFacade->getIndexNames();
        $indexCount = count($indexNames);

        $this->setTotal($indexCount);
        $this->setFiltered($indexCount);

        foreach ($indexNames as $indexName) {
            $tableData[] = [
                static::COL_INDEX => sprintf(
                    '<a href="list-documents?index=%s">%s</a>',
                    $indexName,
                    $indexName
                ),
            ];
        }

        return $tableData;
    }
}
