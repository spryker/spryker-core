<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Communication\Plugin\Search;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface;
use Spryker\Zed\SearchExtension\Dependency\Plugin\StoreAwareInstallPluginInterface;

/**
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 * @method \Spryker\Zed\SearchElasticsearch\Communication\SearchElasticsearchCommunicationFactory getFactory()
 */
class ElasticsearchIndexInstallerPlugin extends AbstractPlugin implements InstallPluginInterface, StoreAwareInstallPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds schema definition files in modules.
     * - Installs or updates Elasticsearch indexes by found schema definition files.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param string|null $storeName
     *
     * @return void
     */
    public function install(LoggerInterface $logger, ?string $storeName = null): void
    {
        $this->getFacade()->install($logger, $storeName);
    }
}
