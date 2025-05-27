<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category;

use Spryker\Shared\Category\CategoryConfig as SharedCategoryConfig;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CategoryConfig extends AbstractBundleConfig
{
    /**
     * Default available template for category
     *
     * @var string
     */
    public const CATEGORY_TEMPLATE_DEFAULT = 'Catalog (default)';

    /**
     * Used as `item_type` for touch mechanism.
     */
    public const RESOURCE_TYPE_CATEGORY_NODE = SharedCategoryConfig::RESOURCE_TYPE_CATEGORY_NODE;

    /**
     * Used as `item_type` for touch mechanism.
     */
    public const RESOURCE_TYPE_NAVIGATION = SharedCategoryConfig::RESOURCE_TYPE_NAVIGATION;

    /**
     * @var string
     */
    protected const REDIRECT_URL_DEFAULT = '/category/root';

    /**
     * @var string
     */
    protected const REDIRECT_URL_CATEGORY_GUI = '/category-gui/list';

    /**
     * @var int
     */
    protected const DEFAULT_CATEGORY_READ_CHUNK = 10000;

    /**
     * @var bool
     */
    protected const DEFAULT_IS_CLOSURE_TABLE_EVENTS_ENABLED = true;

    /**
     * Specification:
     * - Returns the size of the batch retrieval.
     *
     * @api
     *
     * @return int
     */
    public function getCategoryReadChunkSize(): int
    {
        return $this->get(CategoryConstants::CATEGORY_READ_CHUNK, static::DEFAULT_CATEGORY_READ_CHUNK);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getTemplateList()
    {
        return [
            static::CATEGORY_TEMPLATE_DEFAULT => '',
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultRedirectUrl(): string
    {
        if (class_exists('Spryker\Zed\CategoryGui\Communication\Controller\ListController')) {
            return static::REDIRECT_URL_CATEGORY_GUI;
        }

        return static::REDIRECT_URL_DEFAULT;
    }

    /**
     * Specification:
     * - Enables Propel events for `spy_category_closure_table` table.
     * - Impacts category create/update operations.
     *
     * @api
     *
     * @return bool
     */
    public function isCategoryClosureTableEventsEnabled(): bool
    {
        return $this->get(
            CategoryConstants::CATEGORY_IS_CLOSURE_TABLE_EVENTS_ENABLED,
            static::DEFAULT_IS_CLOSURE_TABLE_EVENTS_ENABLED,
        );
    }

    /**
     * Specification:
     * - Defines if full locale name in URL is enabled.
     * - Full locale name in URL will be used instead of language code.
     *
     * @api
     *
     * @return bool
     */
    public function isFullLocaleNamesInUrlEnabled(): bool
    {
        return false;
    }
}
