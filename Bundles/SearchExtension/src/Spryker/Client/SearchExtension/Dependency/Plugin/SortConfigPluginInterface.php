<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

use Spryker\Client\SearchExtension\Config\SortConfigInterface;

interface SortConfigPluginInterface
{
    /**
     * Specification:
     * - Builds sort search configuration.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Config\SortConfigInterface $sortConfigBuilder
     *
     * @return void
     */
    public function buildSortConfig(SortConfigInterface $sortConfigBuilder);
}
