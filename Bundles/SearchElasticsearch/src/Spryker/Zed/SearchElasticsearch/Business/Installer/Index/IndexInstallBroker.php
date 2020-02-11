<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index;

use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface;

class IndexInstallBroker implements IndexInstallBrokerInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface
     */
    protected $indexDefinitionBuilder;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface[]
     */
    protected $installer;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface $indexDefinitionBuilder
     * @param \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface[] $installer
     */
    public function __construct(IndexDefinitionBuilderInterface $indexDefinitionBuilder, array $installer)
    {
        $this->indexDefinitionBuilder = $indexDefinitionBuilder;
        $this->installer = $installer;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function install(LoggerInterface $logger): void
    {
        foreach ($this->indexDefinitionBuilder->build() as $indexDefinitionTransfer) {
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
}
