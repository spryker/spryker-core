<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface TwigConstants
{
    /**
     * Specification:
     * - Configuration options for Yves's twig.
     *
     * @api
     * @see http://twig.sensiolabs.org/doc/api.html#environment-options
     */
    public const YVES_TWIG_OPTIONS = 'YVES_TWIG_OPTIONS';

    /**
     * @deprecated Use `\Spryker\Shared\Twig\TwigConfig::getYvesThemeName()` instead.
     *
     * Specification:
     * - Defines the used theme name for Yves.
     * - Default theme name is "default".
     *
     * @api
     */
    public const YVES_THEME = 'YVES_THEME';

    /**
     * Specification:
     * - Path to cache file for resolved template directories.
     *
     * @api
     */
    public const YVES_PATH_CACHE_FILE = 'YVES_PATH_CACHE_FILE';

    /**
     * Specification:
     * - Defines if the path cache is enabled.
     *
     * @api
     */
    public const YVES_PATH_CACHE_ENABLED = 'YVES_PATH_CACHE_ENABLED';

    /**
     * Specification:
     * - Configuration options for Yves's twig.
     *
     * @api
     * @see http://twig.sensiolabs.org/doc/api.html#environment-options
     */
    public const ZED_TWIG_OPTIONS = 'ZED_TWIG_OPTIONS';

    /**
     * Specification:
     * - Path to cache file for resolved template directories.
     *
     * @api
     */
    public const ZED_PATH_CACHE_FILE = 'ZED_PATH_CACHE_FILE';

    /**
     * Specification:
     * - Defines if the path cache is enabled.
     *
     * @api
     */
    public const ZED_PATH_CACHE_ENABLED = 'ZED_PATH_CACHE_ENABLED';

    /**
     * Specification:
     * - Sets the permission mode for generated directories.
     *
     * @api
     */
    public const DIRECTORY_PERMISSION = 'TWIG:DIRECTORY_PERMISSION';
}
