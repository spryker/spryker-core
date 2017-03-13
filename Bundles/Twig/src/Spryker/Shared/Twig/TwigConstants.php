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
     * - Array of paths where FilesystemLoader should look for template files.
     *
     * @api
     */
    const YVES_FILESYSTEM_LOOKUP_PATHS = 'YVES_FILESYSTEM_LOOKUP_PATHS';

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
     * - Array of paths where FilesystemLoader should look for template files.
     *
     * @api
     */
    const ZED_FILESYSTEM_LOOKUP_PATHS = 'ZED_FILESYSTEM_LOOKUP_PATHS';

}
