<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Config;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Config\FacetConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchConfigBuilderPluginInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class FacetConfigBuilderPlugin extends AbstractPlugin implements FacetSearchConfigBuilderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds catalog search specific facet configuration.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Config\FacetConfigBuilderInterface $facetConfigBuilder
     *
     * @return void
     */
    public function buildFacetConfig(FacetConfigBuilderInterface $facetConfigBuilder): void
    {
        $facetConfigBuilderPlugins = $this->getFactory()->getFacetConfigTransferBuilderPlugins();

        foreach ($facetConfigBuilderPlugins as $facetConfigBuilderPlugin) {
            $facetConfigBuilder->addFacet($facetConfigBuilderPlugin->build());
        }
    }
}
