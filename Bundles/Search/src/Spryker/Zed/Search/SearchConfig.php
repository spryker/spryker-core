<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search;

use Spryker\Shared\Search\SearchConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Search\SearchConfig getSharedConfig()
 */
class SearchConfig extends AbstractBundleConfig
{
    protected const BLACKLIST_SETTINGS_FOR_INDEX_UPDATE = [
        'index.number_of_shards',
        'index.routing_partition_size',
    ];

    protected const STATIC_INDEX_SETTINGS = [
        'index.number_of_shards',
        'index.shard.check_on_startup',
        'index.codec',
        'index.routing_partition_size',
        'analysis',
    ];

    protected const DYNAMIC_INDEX_SETTINGS = [
        'index.number_of_replicas',
        'index.auto_expand_replicas',
        'index.refresh_interval',
        'index.max_result_window',
        'index.max_inner_result_window',
        'index.max_rescore_window',
        'index.max_docvalue_fields_search',
        'index.max_script_fields',
        'index.max_ngram_diff',
        'index.max_shingle_diff',
        'index.blocks.read_only',
        'index.blocks.read_only_allow_delete',
        'index.blocks.read',
        'index.blocks.write',
        'index.blocks.metadata',
        'index.max_refresh_listeners',
        'index.highlight.max_analyzed_offset',
        'index.max_terms_count',
        'index.routing.allocation.enable',
        'index.routing.rebalance.enable',
        'index.gc_deletes',
    ];

    public const INDEX_OPEN_STATE = 'open';

    public const INDEX_CLOSE_STATE = 'close';

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
        if (glob($applicationTransferGlobPattern, GLOB_NOSORT | GLOB_ONLYDIR)) {
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
    public function getBlacklistSettingsForIndexUpdate(): array
    {
        return static::BLACKLIST_SETTINGS_FOR_INDEX_UPDATE;
    }

    /**
     * @return string[]
     */
    public function getStaticIndexSettings()
    {
        return static::STATIC_INDEX_SETTINGS;
    }

    /**
     * @return string[]
     */
    public function getDynamicIndexSettings()
    {
        return static::DYNAMIC_INDEX_SETTINGS;
    }

    /**
     * @return string
     */
    protected function getSprykerRootDir()
    {
        return realpath(__DIR__ . '/../../../../../');
    }

    /**
     * @return int
     */
    public function getPermissionMode(): int
    {
        return $this->get(SearchConstants::DIRECTORY_PERMISSION, 0777);
    }

    /**
     * @return string
     */
    public function getIndexMapClassTemplateDirectory(): string
    {
        return __DIR__ . '/Business/Installer/IndexMap/Generator/Templates/';
    }

    /**
     * @return string
     */
    public function getIndexNameSuffix(): string
    {
        return $this->get(SearchConstants::SEARCH_INDEX_NAME_SUFFIX, '');
    }

    /**
     * @return array
     */
    public function getIndexNameMap(): array
    {
        return $this->getSharedConfig()->getIndexNameNap();
    }
}
