<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\SearchHttp;

use Generated\Shared\Transfer\SearchConfigurationTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class CatalogSearchHttpConfigBuilderPlugin extends AbstractPlugin implements SearchConfigBuilderPluginInterface
{
    /**
     * @uses \Spryker\Shared\SearchHttp\SearchHttpConfig::TYPE_SEARCH_HTTP
     *
     * @var string
     */
    public const TYPE_SEARCH_HTTP = 'TYPE_SEARCH_HTTP';

    /**
     * {@inheritDoc}
     * - Provides Http specific search configuration for catalog search.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchConfigurationTransfer $searchConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationTransfer
     */
    public function buildConfig(SearchConfigurationTransfer $searchConfigurationTransfer): SearchConfigurationTransfer
    {
        $searchConfigurationTransfer = $this->buildFacetConfig($searchConfigurationTransfer);
        $searchConfigurationTransfer = $this->buildSortConfig($searchConfigurationTransfer);
        $searchConfigurationTransfer = $this->buildPaginationConfig($searchConfigurationTransfer);

        return $searchConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigurationTransfer $searchConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationTransfer
     */
    protected function buildFacetConfig(SearchConfigurationTransfer $searchConfigurationTransfer): SearchConfigurationTransfer
    {
        foreach ($this->getFactory()->getFacetConfigTransferBuilderPluginVariants(static::TYPE_SEARCH_HTTP) as $facetConfigBuilderPlugin) {
            $searchConfigurationTransfer->addFacetConfigItem($facetConfigBuilderPlugin->build());
        }

        return $searchConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigurationTransfer $searchConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationTransfer
     */
    protected function buildSortConfig(SearchConfigurationTransfer $searchConfigurationTransfer): SearchConfigurationTransfer
    {
        $sortConfigBuilderPlugins = $this->getFactory()->getSortConfigTransferBuilderPlugins();

        foreach ($sortConfigBuilderPlugins as $sortConfigBuilderPlugin) {
            $searchConfigurationTransfer->addSortConfigItem($sortConfigBuilderPlugin->build());
        }

        return $searchConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigurationTransfer $searchConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationTransfer
     */
    protected function buildPaginationConfig(SearchConfigurationTransfer $searchConfigurationTransfer): SearchConfigurationTransfer
    {
        $searchConfigurationTransfer->setPaginationConfig(
            $this->getFactory()->getConfig()->getCatalogSearchPaginationConfigTransfer(),
        );

        return $searchConfigurationTransfer;
    }
}
