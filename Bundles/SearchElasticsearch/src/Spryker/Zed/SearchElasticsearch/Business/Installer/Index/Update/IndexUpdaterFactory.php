<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update;

use Elastica\Client;
use Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetectorInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface;

class IndexUpdaterFactory implements IndexUpdaterFactoryInterface
{
    /**
     * @var \Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetectorInterface
     */
    protected $mappingTypeSupportDetector;

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
     * @param \Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetectorInterface $mappingTypeSupportDetector
     */
    public function __construct(Client $client, MappingBuilderInterface $mappingBuilder, MappingTypeSupportDetectorInterface $mappingTypeSupportDetector)
    {
        $this->client = $client;
        $this->mappingBuilder = $mappingBuilder;
        $this->mappingTypeSupportDetector = $mappingTypeSupportDetector;
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface
     */
    public function createIndexUpdater(): InstallerInterface
    {
        if ($this->mappingTypeSupportDetector->isMappingTypesSupported()) {
            return new MappingTypeAwareIndexUpdater($this->client, $this->mappingBuilder);
        }

        return new IndexUpdater($this->client, $this->mappingBuilder);
    }
}
