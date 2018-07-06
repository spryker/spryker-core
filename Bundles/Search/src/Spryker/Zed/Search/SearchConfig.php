<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search;

use Spryker\Shared\Search\SearchConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SearchConfig extends AbstractBundleConfig
{
    protected const INDEX_SETTINGS_BLACKLIST = [
        'index.number_of_shards',
        'index.codec',
        'index.routing_partition_size',
        'index.shard.check_on_startup',
        'analysis',
    ];

    /**
     * @return string
     */
    public function getElasticaDocumentType()
    {
        return $this->get(SearchConstants::ELASTICA_PARAMETER__DOCUMENT_TYPE);
    }

    /**
     * @return string
     */
    public function getReindexUrl()
    {
        return sprintf(
            '%s:%s/_reindex?pretty',
            $this->get(SearchConstants::ELASTICA_PARAMETER__HOST),
            $this->get(SearchConstants::ELASTICA_PARAMETER__PORT)
        );
    }

    /**
     * @return array
     */
    public function getJsonIndexDefinitionDirectories()
    {
        $directories = [
            $this->getSprykerRootDir() . '/*/src/*/Shared/*/IndexMap/',
        ];

        $applicationTransferGlobPattern = APPLICATION_SOURCE_DIR . '/*/Shared/*/IndexMap/';
        if (glob($applicationTransferGlobPattern)) {
            $directories[] = $applicationTransferGlobPattern;
        }

        return $directories;
    }

    /**
     * @return string
     */
    public function getClassTargetDirectory()
    {
        return APPLICATION_SOURCE_DIR . '/Generated/Shared/Search/';
    }

    /**
     * @return string[]
     */
    public function getIndexDefinitionBlacklistedSettings(): array
    {
        return static::INDEX_SETTINGS_BLACKLIST;
    }

    /**
     * @return string
     */
    protected function getSprykerRootDir()
    {
        return realpath(__DIR__ . '/../../../../../');
    }
}
