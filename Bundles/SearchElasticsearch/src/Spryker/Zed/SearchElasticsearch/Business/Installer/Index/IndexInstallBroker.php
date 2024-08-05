<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index;

use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface;
use Spryker\Zed\SearchElasticsearch\Dependency\Facade\SearchElasticsearchToStoreFacadeInterface;

class IndexInstallBroker implements IndexInstallBrokerInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface
     */
    protected $indexDefinitionBuilder;

    /**
     * @var array<\Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface>
     */
    protected $installer;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Dependency\Facade\SearchElasticsearchToStoreFacadeInterface
     */
    protected SearchElasticsearchToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface $indexDefinitionBuilder
     * @param array<\Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface> $installer
     * @param \Spryker\Zed\SearchElasticsearch\Dependency\Facade\SearchElasticsearchToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        IndexDefinitionBuilderInterface $indexDefinitionBuilder,
        array $installer,
        SearchElasticsearchToStoreFacadeInterface $storeFacade
    ) {
        $this->indexDefinitionBuilder = $indexDefinitionBuilder;
        $this->installer = $installer;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param string|null $storeName
     *
     * @return void
     */
    public function install(LoggerInterface $logger, ?string $storeName): void
    {
        foreach ($this->getGetIndexDefinitionTransfers($storeName) as $indexDefinitionTransfer) {
            $this->installIndexDefinition($indexDefinitionTransfer, $logger);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    protected function installIndexDefinition(IndexDefinitionTransfer $indexDefinitionTransfer, LoggerInterface $logger): void
    {
        foreach ($this->installer as $installer) {
            if ($installer->accept($indexDefinitionTransfer)) {
                $installer->run($indexDefinitionTransfer, $logger);
            }
        }
    }

    /**
     * @param string|null $storeName
     *
     * @return array<\Generated\Shared\Transfer\IndexDefinitionTransfer>
     */
    protected function getGetIndexDefinitionTransfers(?string $storeName): array
    {
        if ($storeName) {
            $this->indexDefinitionBuilder->build($storeName);
        }

        /* Required by infrastructure, exists only for BC reasons with DMS mode. */
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $this->indexDefinitionBuilder->build($this->getCurrentStore());
        }

        $indexDefinitionTransfers = [];
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $indexDefinitionTransfers = array_merge($indexDefinitionTransfers, $this->indexDefinitionBuilder->build($storeTransfer->getName()));
        }

        return $indexDefinitionTransfers;
    }

    /**
     * @return string
     */
    protected function getCurrentStore(): string
    {
        return APPLICATION_STORE;
    }
}
