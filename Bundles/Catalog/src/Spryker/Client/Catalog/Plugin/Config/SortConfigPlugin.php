<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Config;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class SortConfigPlugin extends AbstractPlugin implements SortConfigPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds catalog search specific sort configuration.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Config\SortConfigInterface $sortConfigBuilder
     *
     * @return void
     */
    public function buildSortConfig(SortConfigInterface $sortConfigBuilder): void
    {
        $sortConfigBuilderPlugins = $this->getFactory()->getSortConfigTransferBuilderPlugins();

        foreach ($sortConfigBuilderPlugins as $sortConfigBuilderPlugin) {
            $sortConfigBuilder->addSort($sortConfigBuilderPlugin->build());
        }
    }
}
