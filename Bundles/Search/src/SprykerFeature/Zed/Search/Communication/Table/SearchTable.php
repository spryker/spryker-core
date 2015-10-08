<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search\Communication\Table;

use Elastica\Exception\ResponseException;
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

        $config->setUrl('list-ajax');

        return $config;
    }

    /**
     * @param TableConfiguration $config
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
