<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

interface SearchConfigBuilderInterface
{
    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    public function build(): SearchConfigInterface;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface[] $searchConfigBuilderPlugins
     *
     * @return void
     */
    public function setSearchConfigBuilderPlugins(array $searchConfigBuilderPlugins): void;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[] $searchConfigExpanderPlugins
     *
     * @return void
     */
    public function setSearchConfigExpanderPlugins(array $searchConfigExpanderPlugins): void;
}
