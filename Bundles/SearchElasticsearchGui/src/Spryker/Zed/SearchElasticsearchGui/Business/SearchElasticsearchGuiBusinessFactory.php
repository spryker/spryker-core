<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SearchElasticsearchGui\Business\DocumentReader\DocumentReader;
use Spryker\Zed\SearchElasticsearchGui\Business\DocumentReader\DocumentReaderInterface;
use Spryker\Zed\SearchElasticsearchGui\Dependency\Client\SearchElasticsearchGuiToSearchElasticsearchClientInterface;
use Spryker\Zed\SearchElasticsearchGui\SearchElasticsearchGuiDependencyProvider;

class SearchElasticsearchGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SearchElasticsearchGui\Business\DocumentReader\DocumentReaderInterface
     */
    public function createDocumentReader(): DocumentReaderInterface
    {
        return new DocumentReader(
            $this->getSearchElasticsearchClient()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearchGui\Dependency\Client\SearchElasticsearchGuiToSearchElasticsearchClientInterface
     */
    public function getSearchElasticsearchClient(): SearchElasticsearchGuiToSearchElasticsearchClientInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchGuiDependencyProvider::CLIENT_SEARCH_ELASTICSEARCH);
    }
}
