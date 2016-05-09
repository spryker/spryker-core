<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Search\Communication\Form\FilterForm;
use Spryker\Zed\Search\Communication\Table\FiltersTable;
use Spryker\Zed\Search\Communication\Table\SearchTable;
use Spryker\Zed\Search\SearchDependencyProvider;

/**
 * @method \Spryker\Zed\Search\SearchConfig getConfig()
 */
class SearchCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Storage\Communication\Table\StorageTable
     */
    public function createSearchTable()
    {
        $searchClient = $this->getSearchClient();

        return new SearchTable($searchClient);
    }

    /**
     * @return \Spryker\Client\Search\SearchClient
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\Search\Dependency\Facade\SearchToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return string
     */
    public function getElasticaDocumentType()
    {
        return $this->getConfig()->getElasticaDocumentType();
    }

    /**
     * @return \Spryker\Zed\Search\Communication\Table\FiltersTable
     */
    public function createFiltersTable()
    {
        return new FiltersTable();
    }

    public function createFilterForm(array $data = [], array $options = [])
    {
        $filterFormType = new FilterForm();

        return $this->getFormFactory()->create($filterFormType, $data, $options);
    }

}
