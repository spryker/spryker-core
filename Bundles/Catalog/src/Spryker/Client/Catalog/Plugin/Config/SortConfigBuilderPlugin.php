<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Config;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Config\SortConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortSearchConfigBuilderPluginInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class SortConfigBuilderPlugin extends AbstractPlugin implements SortSearchConfigBuilderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds catalog search specific sort configuration.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Config\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return void
     */
    public function buildSortConfig(SortConfigBuilderInterface $sortConfigBuilder)
    {
        $sortConfigBuilderPlugins = $this->getFactory()->getSortConfigTransferBuilderPlugins();

        foreach ($sortConfigBuilderPlugins as $sortConfigBuilderPlugin) {
            $sortConfigBuilder->addSort($sortConfigBuilderPlugin->build());
        }
    }
}
