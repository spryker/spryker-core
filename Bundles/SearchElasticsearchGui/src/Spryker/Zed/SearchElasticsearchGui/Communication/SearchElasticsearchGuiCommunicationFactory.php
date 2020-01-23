<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Communication;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SearchElasticsearchGui\Communication\Plugin\Query\DocumentListQuery;
use Spryker\Zed\SearchElasticsearchGui\Communication\Table\DocumentTable;
use Spryker\Zed\SearchElasticsearchGui\Communication\Table\IndexTable;
use Spryker\Zed\SearchElasticsearchGui\Dependency\Client\SearchElasticsearchGuiToSearchElasticsearchClientInterface;
use Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeInterface;
use Spryker\Zed\SearchElasticsearchGui\SearchElasticsearchGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SearchElasticsearchGui\Business\SearchElasticsearchGuiFacadeInterface getFacade()
 */
class SearchElasticsearchGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createIndexTable(): AbstractTable
    {
        return new IndexTable(
            $this->getSearchElasticsearchFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createDocumentTable(): AbstractTable
    {
        return new DocumentTable(
            $this->getSearchElasticsearchClient(),
            $this->createDocumentListQuery()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearchGui\Dependency\Client\SearchElasticsearchGuiToSearchElasticsearchClientInterface
     */
    public function getSearchElasticsearchClient(): SearchElasticsearchGuiToSearchElasticsearchClientInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchGuiDependencyProvider::CLIENT_SEARCH_ELASTICSEARCH);
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeInterface
     */
    public function getSearchElasticsearchFacade(): SearchElasticsearchGuiToSearchElasticsearchFacadeInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchGuiDependencyProvider::FACADE_SEARCH_ELASTICSEARCH);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createDocumentListQuery(): QueryInterface
    {
        return new DocumentListQuery();
    }
}
