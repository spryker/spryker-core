<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

use Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface;

interface SearchConfigBuilderInterface
{
    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    public function build(): SearchConfigInterface;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface $searchConfigBuilderPlugin
     *
     * @return void
     */
    public function setSearchConfigBuilderPlugin(SearchConfigBuilderPluginInterface $searchConfigBuilderPlugin): void;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[] $searchConfigExpanderPlugins
     *
     * @return void
     */
    public function setSearchConfigExpanderPlugins(array $searchConfigExpanderPlugins): void;
}
