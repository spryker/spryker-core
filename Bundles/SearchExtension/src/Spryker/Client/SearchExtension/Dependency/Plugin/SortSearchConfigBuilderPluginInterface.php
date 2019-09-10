<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

use Spryker\Client\SearchExtension\Config\SortConfigBuilderInterface;

interface SortSearchConfigBuilderPluginInterface
{
    /**
     * Specification:
     * - Builds sort search configuration.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Config\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return void
     */
    public function buildSortConfig(SortConfigBuilderInterface $sortConfigBuilder);
}
