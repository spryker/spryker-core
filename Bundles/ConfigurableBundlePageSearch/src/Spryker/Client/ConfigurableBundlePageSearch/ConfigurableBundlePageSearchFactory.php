<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundlePageSearch;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Spryker\Client\ConfigurableBundlePageSearch\Dependency\Client\ConfigurableBundlePageSearchToSearchClientInterface;
use Spryker\Client\ConfigurableBundlePageSearch\Plugin\Elasticsearch\Query\ConfigurableBundleTemplatePageSearchQueryPlugin;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class ConfigurableBundlePageSearchFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function createConfigurableBundleTemplateSearchQuery(ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer): QueryInterface
    {
        return new ConfigurableBundleTemplatePageSearchQueryPlugin($configurableBundleTemplatePageSearchRequestTransfer);
    }

    /**
     * @return \Spryker\Client\ConfigurableBundlePageSearch\Dependency\Client\ConfigurableBundlePageSearchToSearchClientInterface
     */
    public function getSearchClient(): ConfigurableBundlePageSearchToSearchClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getConfigurableBundleTemplateResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_RESULT_FORMATTER);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getConfigurableBundleTemplatePageSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_QUERY_EXPANDER);
    }
}
