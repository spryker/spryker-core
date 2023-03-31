<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchExtension\Dependency\Plugin;

use Psr\Log\LoggerInterface;

/**
 * Use this plugin to install the required store-aware activities for a particular search provider.
 */
interface StoreAwareInstallPluginInterface
{
    /**
     * Specification:
     * - Performs various installation activities required for search.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param string|null $storeName
     *
     * @return void
     */
    public function install(LoggerInterface $logger, ?string $storeName = null): void;
}
