<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Table;

use SprykerFeature\Client\Search\Service\SearchClient;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class SearchTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    /**
     * @var int
     */
    protected $defaultLimit = 1000;

    /**
     * @var SearchClient
     */
    protected $searchClient;

    /**
     * @param SearchClient $searchClient
     */
    public function __construct(SearchClient $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
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

        $config->setUrl('search-table');

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->searchClient->getIndexClient()->search('*');
        $totalHits = $query->getTotalHits();
        $this->setTotal($totalHits);
        $this->setFiltered($totalHits);

        $offset = $this->getOffset();
        $limit = $this->getLimit();

        $results = $this->searchClient->getIndexClient()->search('*', ['limit' => $limit, 'from' => $offset])->getResults();

        $tableData = [];

        foreach ($results as $result) {
            $tableData[] = [
                'id' => '<a href="/maintenance/search/search-key?key=' . $result->getId() . '">' . $result->getId() . '</a>',
                'index' => $result->getIndex(),
                'type' => $result->getType(),
                'score' => $result->getScore(),
            ];
        }

        return $tableData;
    }

}
