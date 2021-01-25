<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Communication\Table;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\SearchElasticsearchGui\Communication\Controller\MaintenanceController;
use Spryker\Zed\SearchElasticsearchGui\Dependency\Client\SearchElasticsearchGuiToSearchElasticsearchClientInterface;

class DocumentTable extends AbstractTable
{
    protected const COL_ID = 'COL_ID';
    protected const COL_INDEX = 'COL_INDEX';
    protected const COL_SCORE = 'COL_SCORE';

    /**
     * @var \Spryker\Zed\SearchElasticsearchGui\Dependency\Client\SearchElasticsearchGuiToSearchElasticsearchClientInterface
     */
    protected $searchElasticsearchClient;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface
     */
    protected $documentListQuery;

    /**
     * @param \Spryker\Zed\SearchElasticsearchGui\Dependency\Client\SearchElasticsearchGuiToSearchElasticsearchClientInterface $searchElasticsearchClient
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface $documentListQuery
     */
    public function __construct(
        SearchElasticsearchGuiToSearchElasticsearchClientInterface $searchElasticsearchClient,
        $documentListQuery
    ) {
        $this->searchElasticsearchClient = $searchElasticsearchClient;
        $this->documentListQuery = $documentListQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->configureDocumentListQuery();

        $headers = [
            static::COL_ID => 'Id',
            static::COL_SCORE => 'Score',
        ];

        $config->setHeader($headers);
        $config->setRawColumns([
            static::COL_ID,
        ]);
        $config->setUrl(
            sprintf(
                'list-documents-ajax?index=%s',
                $this->getIndexName()
            )
        );
        $config->setDefaultSortField(static::COL_ID);

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

        /** @var \Elastica\ResultSet $results */
        $results = $this->searchElasticsearchClient->search(
            $this->documentListQuery
        );

        $this->setTotal($results->getTotalHits());
        $this->setFiltered($results->getTotalHits());

        foreach ($results as $result) {
            $tableData[] = [
                static::COL_ID => sprintf(
                    '<a href="/search-elasticsearch-gui/maintenance/document-info?documentId=%s&index=%s">%s</a>',
                    $result->getId(),
                    $result->getIndex(),
                    $result->getId()
                ),
                static::COL_INDEX => $result->getIndex(),
                static::COL_SCORE => $result->getScore(),
            ];
        }

        return $tableData;
    }

    /**
     * @return void
     */
    protected function configureDocumentListQuery(): void
    {
        $this->documentListQuery->setSearchContext(
            $this->createSearchContextTransfer()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function createSearchContextTransfer(): SearchContextTransfer
    {
        $elasticsearchContextTransfer = (new ElasticsearchSearchContextTransfer())
            ->setLimit($this->getLimit())
            ->setOffset($this->getOffset())
            ->setSearchString($this->getSearchString())
            ->setIndexName($this->getIndexName());

        return (new SearchContextTransfer())->setElasticsearchContext($elasticsearchContextTransfer);
    }

    /**
     * @return string|null
     */
    protected function getIndexName(): ?string
    {
        return $this->request->query->get(MaintenanceController::URL_PARAM_INDEX);
    }

    /**
     * @return string|null
     */
    protected function getSearchString(): ?string
    {
        return !$this->getSearchTerm() ?: $this->getSearchTerm()['value'];
    }
}
