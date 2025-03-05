<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Sitemap;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SitemapConstants
{
    /**
     * Specification:
     * - Defines the mapping of store to Yves host.
     *
     * @api
     *
     * @see \Spryker\Shared\AvailabilityNotification\AvailabilityNotificationConstants::STORE_TO_YVES_HOST_MAPPING
     *
     * @var string
     */
    public const STORE_TO_YVES_HOST_MAPPING = 'SITEMAP:STORE_TO_YVES_HOST_MAPPING';

    /**
     * Specification:
     * - Defines the mapping of region to Yves host.
     *
     * @api
     *
     * @see \Spryker\Shared\AvailabilityNotification\AvailabilityNotificationConstants::REGION_TO_YVES_HOST_MAPPING
     *
     * @var string
     */
    public const REGION_TO_YVES_HOST_MAPPING = 'SITEMAP:REGION_TO_YVES_HOST_MAPPING';

    /**
     * Specification:
     * - Defines the base URL for Yves.
     *
     * @api
     *
     * @see \Spryker\Shared\AvailabilityNotification\AvailabilityNotificationConstants::BASE_URL_YVES_PORT
     *
     * @var string
     */
    public const BASE_URL_YVES_PORT = 'SITEMAP:BASE_URL_YVES_PORT';

    /**
     * Specification:
     * - Defines the filesystem name for storing sitemap files.
     * - Used as a key in the filesystem service configuration.
     *
     * @api
     *
     * @var string
     */
    public const FILESYSTEM_NAME = 'sitemap-storage';

    /**
     * Specification:
     * - Defines the filesystem name for storing sitemap cache files.
     *
     * @api
     *
     * @var string
     */
    public const FILESYSTEM_NAME_CACHE = 'sitemap-storage-cache';

    /**
     * Specification:
     * - Defines the sitemap file name prefix.
     *
     * @api
     *
     * @var string
     */
    public const SITEMAP_FILE_NAME_PREFIX = 'sitemap';

    /**
     * Specification:
     * - Defines the sitemap index file name.
     *
     * @api
     *
     * @var string
     */
    public const SITEMAP_INDEX_FILE_NAME = 'sitemap.xml';
}
