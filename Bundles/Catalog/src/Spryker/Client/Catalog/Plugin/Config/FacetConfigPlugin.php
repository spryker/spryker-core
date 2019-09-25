<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Config;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Config\FacetConfigInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigPluginInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class FacetConfigPlugin extends AbstractPlugin implements FacetConfigPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds catalog search specific facet configuration.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Config\FacetConfigInterface $facetConfigBuilder
     *
     * @return void
     */
    public function buildFacetConfig(FacetConfigInterface $facetConfigBuilder): void
    {
        $facetConfigBuilderPlugins = $this->getFactory()->getFacetConfigTransferBuilderPlugins();

        foreach ($facetConfigBuilderPlugins as $facetConfigBuilderPlugin) {
            $facetConfigBuilder->addFacet($facetConfigBuilderPlugin->build());
        }
    }
}
