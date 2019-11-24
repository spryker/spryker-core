<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch;

use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig getSharedConfig()
 */
class SearchElasticsearchConfig extends AbstractBundleConfig
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

    protected const RESOURCE_NAME_PRODUCT_ABSTRACT = 'product_abstract';
    protected const RESOURCE_NAME_PRODUCT_CONCRETE = 'product_concrete';
    protected const RESOURCE_NAME_CATEGORY_NODE = 'category_node';
    protected const RESOURCE_NAME_CMS_PAGE_SEARCH = 'cms_page_search';
    protected const RESOURCE_NAME_PRODUCT_REVIEW = 'product_review';
    protected const RESOURCE_NAME_PRODUCT_SET = 'product_set';

    protected const APPLICABLE_MAPPING_RESOURCE_TYPES = [
        self::RESOURCE_NAME_PRODUCT_ABSTRACT,
        self::RESOURCE_NAME_PRODUCT_CONCRETE,
        self::RESOURCE_NAME_CATEGORY_NODE,
        self::RESOURCE_NAME_CMS_PAGE_SEARCH,
        self::RESOURCE_NAME_PRODUCT_REVIEW,
        self::RESOURCE_NAME_PRODUCT_SET,
    ];

    protected const PAGE_DATA_MAP_RESOURCE_TYPES = [
        self::RESOURCE_NAME_PRODUCT_ABSTRACT,
        self::RESOURCE_NAME_PRODUCT_CONCRETE,
        self::RESOURCE_NAME_CATEGORY_NODE,
        self::RESOURCE_NAME_CMS_PAGE_SEARCH,
        self::RESOURCE_NAME_PRODUCT_SET,
    ];

    public const INDEX_OPEN_STATE = 'open';
    public const INDEX_CLOSE_STATE = 'close';

    /**
     * @return string
     */
    public function getReindexUrl(): string
    {
        return '_reindex?pretty=true';
    }

    /**
     * @return array
     */
    public function getJsonSchemaDefinitionDirectories(): array
    {
        $directories = [];

        $directory = sprintf('%s/vendor/spryker/*/src/*/Shared/*/Schema/', APPLICATION_ROOT_DIR);
        if (glob($directory, GLOB_NOSORT | GLOB_ONLYDIR)) {
            $directories[] = $directory;
        }

        $applicationTransferGlobPattern = APPLICATION_SOURCE_DIR . '/*/Shared/*/Schema/';
        if (glob($applicationTransferGlobPattern, GLOB_NOSORT | GLOB_ONLYDIR)) {
            $directories[] = $applicationTransferGlobPattern;
        }

        return $directories;
    }

    /**
     * @return string
     */
    public function getClassTargetDirectory(): string
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
    public function getStaticIndexSettings(): array
    {
        return static::STATIC_INDEX_SETTINGS;
    }

    /**
     * @return string[]
     */
    public function getDynamicIndexSettings(): array
    {
        return static::DYNAMIC_INDEX_SETTINGS;
    }

    /**
     * @return int
     */
    public function getPermissionMode(): int
    {
        return $this->get(SearchElasticsearchConstants::DIRECTORY_PERMISSION, 0777);
    }

    /**
     * @return string
     */
    public function getIndexMapClassTemplateDirectory(): string
    {
        return __DIR__ . '/Business/Installer/IndexMap/Generator/Templates/';
    }

    /**
     * @return array
     */
    public function getClientConfig(): array
    {
        return $this->getSharedConfig()->getClientConfig();
    }

    /**
     * @return string[]
     */
    public function getSupportedSourceIdentifiers(): array
    {
        return $this->getSharedConfig()->getSupportedSourceIdentifiers();
    }

    /**
     * @return string
     */
    public function getProductAbstractResourceName(): string
    {
        return static::RESOURCE_NAME_PRODUCT_ABSTRACT;
    }

    /**
     * @return string
     */
    public function getProductConcreteResourceName(): string
    {
        return static::RESOURCE_NAME_PRODUCT_ABSTRACT;
    }

    /**
     * @return string
     */
    public function getCategoryNodeResourceName(): string
    {
        return static::RESOURCE_NAME_PRODUCT_ABSTRACT;
    }

    /**
     * @return string
     */
    public function getCmsPageSearchResourceName(): string
    {
        return static::RESOURCE_NAME_PRODUCT_ABSTRACT;
    }

    /**
     * @return string
     */
    public function getProductReviewResourceName(): string
    {
        return static::RESOURCE_NAME_PRODUCT_REVIEW;
    }

    /**
     * @return array
     */
    public function getApplicableMappingResourceNames(): array
    {
        return static::APPLICABLE_MAPPING_RESOURCE_TYPES;
    }

    /**
     * @return string[]
     */
    public function getPageDataMapResourceNames(): array
    {
        return static::PAGE_DATA_MAP_RESOURCE_TYPES;
    }
}
