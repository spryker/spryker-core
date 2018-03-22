<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

interface TwigConstants
{
    /**
     * Specification:
     * - Configuration options for Yves's twig.
     *
     * @api
     * @see http://twig.sensiolabs.org/doc/api.html#environment-options
     */
    const YVES_TWIG_OPTIONS = 'YVES_TWIG_OPTIONS';

    /**
     * Specification:
     * - Defines the used theme name for Yves.
     * - Default theme name is "default".
     *
     * @api
     */
    const YVES_THEME = 'YVES_THEME';

    /**
     * Specification:
     * - Path to cache file for resolved template directories.
     *
     * @api
     */
    const YVES_PATH_CACHE_FILE = 'YVES_PATH_CACHE_FILE';

    /**
     * Specification:
     * - Defines if the path cache is enabled.
     *
     * @api
     */
    const YVES_PATH_CACHE_ENABLED = 'YVES_PATH_CACHE_ENABLED';

    /**
     * Specification:
     * - Configuration options for Yves's twig.
     *
     * @api
     * @see http://twig.sensiolabs.org/doc/api.html#environment-options
     */
    const ZED_TWIG_OPTIONS = 'ZED_TWIG_OPTIONS';

    /**
     * Specification:
     * - Path to cache file for resolved template directories.
     *
     * @api
     */
    const ZED_PATH_CACHE_FILE = 'ZED_PATH_CACHE_FILE';

    /**
     * Specification:
     * - Defines if the path cache is enabled.
     *
     * @api
     */
    const ZED_PATH_CACHE_ENABLED = 'ZED_PATH_CACHE_ENABLED';
}
