<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Config;

interface SearchConfigBuilderInterface
{
    /**
     * @return \Spryker\Client\SearchHttp\Config\SearchConfigInterface
     */
    public function build(): SearchConfigInterface;

    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface> $searchConfigBuilderPlugins
     *
     * @return void
     */
    public function setSearchConfigBuilderPlugins(array $searchConfigBuilderPlugins): void;

    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface> $searchConfigExpanderPlugins
     *
     * @return void
     */
    public function setSearchConfigExpanderPlugins(array $searchConfigExpanderPlugins): void;
}
