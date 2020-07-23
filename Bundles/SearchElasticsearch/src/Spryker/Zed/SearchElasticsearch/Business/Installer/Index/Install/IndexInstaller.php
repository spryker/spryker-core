<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Install;

use Elastica\Client;
use Elastica\Request;
use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface;

class IndexInstaller implements InstallerInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface
     */
    protected $mappingBuilder;

    /**
     * @param \Elastica\Client $client
     * @param \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface $mappingBuilder
     */
    public function __construct(Client $client, MappingBuilderInterface $mappingBuilder)
    {
        $this->client = $client;
        $this->mappingBuilder = $mappingBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinitionTransfer
     *
     * @return bool
     */
    public function accept(IndexDefinitionTransfer $indexDefinitionTransfer): bool
    {
        $index = $this->client->getIndex($indexDefinitionTransfer->getIndexName());

        return !$index->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function run(IndexDefinitionTransfer $indexDefinitionTransfer, LoggerInterface $logger): void
    {
        $index = $this->client->getIndex($indexDefinitionTransfer->getIndexName());
        $mapping = $this->mappingBuilder->buildMapping($indexDefinitionTransfer->getMappings(), $index);

        $data = ['mappings' => $mapping->toArray()];
        $settings = $indexDefinitionTransfer->getSettings();
        if ($settings) {
            $data['settings'] = $settings;
        }

        $logger->info(sprintf('Import mappings and settings for index "%s".', $index->getName()));

        $index->request('', Request::PUT, $data);
    }
}
