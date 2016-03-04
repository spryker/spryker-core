<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Table;

use Elastica\Exception\ResponseException;
use Spryker\Client\Search\SearchClient;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SearchTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    /**
     * @var int
     */
    protected $defaultLimit = 1000;

    /**
     * @var \Spryker\Client\Search\SearchClient
     */
    protected $searchClient;

    /**
     * @param \Spryker\Client\Search\SearchClient $searchClient
     */
    public function __construct(SearchClient $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $headers = [
            'id' => 'Id',
            'index' => 'Index',
            'type' => 'Type',
            'score' => 'Score',
        ];

        $config->setHeader($headers);

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

        try {
            $query = $this->searchClient->getIndexClient()->search('*');
            $totalHits = $query->getTotalHits();
            $this->setTotal($totalHits);
            $this->setFiltered($totalHits);

            $offset = $this->getOffset();
            $limit = $this->getLimit();

            $results = $this->searchClient->getIndexClient()->search('*', ['limit' => $limit, 'from' => $offset])->getResults();

            foreach ($results as $result) {
                $tableData[] = [
                    'id' => '<a href="/search/maintenance/key?key=' . $result->getId() . '">' . $result->getId() . '</a>',
                    'index' => $result->getIndex(),
                    'type' => $result->getType(),
                    'score' => $result->getScore(),
                ];
            }
        } catch (ResponseException $e) {
            // allowed catch, because ElasticSearch index is not always there
        }

        return $tableData;
    }

}
