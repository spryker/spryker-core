<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Table;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Search\Business\SearchFacadeInterface;

class SearchTable extends AbstractTable
{
    /**
     * @var string
     */
    public const COL_ID = 'id';

    /**
     * @var string
     */
    public const COL_INDEX = 'index';

    /**
     * @var string
     */
    public const COL_TYPE = 'type';

    /**
     * @var string
     */
    public const COL_SCORE = 'score';

    /**
     * @var \Spryker\Zed\Search\Business\SearchFacadeInterface
     */
    protected $searchFacade;

    /**
     * @param \Spryker\Zed\Search\Business\SearchFacadeInterface $searchFacade
     */
    public function __construct(SearchFacadeInterface $searchFacade)
    {
        $this->searchFacade = $searchFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $headers = [
            static::COL_ID => 'Id',
            static::COL_INDEX => 'Index',
            static::COL_TYPE => 'Type',
            static::COL_SCORE => 'Score',
        ];

        $config->setHeader($headers);
        $config->setRawColumns([
            static::COL_ID,
        ]);
        $config->setDefaultSortColumnIndex(3);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $config->setUrl('list-ajax');

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $tableData = [];

        $searchString = !$this->getSearchTerm() ?: $this->getSearchTerm()['value'];

        $results = $this
            ->searchFacade
            ->searchKeys($searchString, $this->getLimit(), $this->getOffset());

        $this->setTotal($results->getTotalHits());
        $this->setFiltered($results->getTotalHits());

        foreach ($results as $result) {
            $tableData[] = [
                static::COL_ID => '<a href="/search/maintenance/key?key=' . $result->getId() . '">' . $result->getId() . '</a>',
                static::COL_INDEX => $result->getIndex(),
                static::COL_TYPE => $result->getType(),
                static::COL_SCORE => $result->getScore(),
            ];
        }

        return $tableData;
    }
}
