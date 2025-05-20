<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sitemap;

use Spryker\Shared\Sitemap\SitemapConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Sitemap\SitemapConfig getSharedConfig()
 */
class SitemapConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const SITEMAP_URL_LIMIT = 50000;

    /**
     * @var int
     */
    protected const SITEMAP_GENERATOR_ENTITY_LIMIT = 5000;

    /**
     * Specification:
     * - Returns the maximum number of URLs that can be added to a sitemap.
     *
     * @api
     *
     * @return int
     */
    public function getSitemapUrlLimit(): int
    {
        return static::SITEMAP_URL_LIMIT;
    }

    /**
     * Specification:
     * - Returns the file path for the given store and file name.
     *
     * @api
     *
     * @param string $storeName
     * @param string $fileName
     *
     * @return string
     */
    public function getFilePath(string $storeName, string $fileName): string
    {
        return $this->getSharedConfig()->getFilePath($storeName, $fileName);
    }

    /**
     * Specification:
     * - Returns the store to Yves host mapping.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getStoreToYvesHostMapping(): array
    {
        return $this->get(SitemapConstants::STORE_TO_YVES_HOST_MAPPING, []);
    }

    /**
     * Specification:
     * - Returns regions to Yves host mapping.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getRegionToYvesHostMapping(): array
    {
        return $this->get(SitemapConstants::REGION_TO_YVES_HOST_MAPPING, []);
    }

    /**
     * Specification:
     * - Returns the base URL for Yves port.
     *
     * @api
     *
     * @return int
     */
    public function getBaseUrlYvesPort(): int
    {
        return $this->get(SitemapConstants::BASE_URL_YVES_PORT);
    }

    /**
     * Specification:
     * - Returns current region if defined.
     *
     * @api
     *
     * @return string|null
     */
    public function getCurrentRegion(): ?string
    {
        if (defined('APPLICATION_REGION')) {
            return APPLICATION_REGION;
        }

        return null;
    }

    /**
     * Specification:
     * - Returns the maximum number of entities that generator must return per one iteration.
     *
     * @api
     *
     * @return int
     */
    public function getGeneratorEnitityLimit(): int
    {
        return static::SITEMAP_GENERATOR_ENTITY_LIMIT;
    }
}
