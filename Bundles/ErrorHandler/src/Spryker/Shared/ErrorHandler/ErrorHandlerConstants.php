<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

interface ErrorHandlerConstants
{

    /**
     * Specification:
     * - Absolute path to an HTML file which contains the error page for Zed. E.g. /var/www/public/Zed/error-page.html
     *
     * @api
     */
    const ZED_ERROR_PAGE = 'ZED_ERROR_PAGE';

    /**
     * Specification:
     * - Absolute path to an HTML file which contains the error page for Yves. E.g. /var/www/public/Yves/error-page.html
     *
     * @api
     */
    const YVES_ERROR_PAGE = 'YVES_ERROR_PAGE';

    /**
     * Specification:
     * - Class name of class which implements ErrorRendererInterface and should be used to render a given exception.
     *
     * @api
     */
    const ERROR_RENDERER = 'ERROR_RENDERER';

    /**
     * Specification:
     * - Sets which PHP error levels are reported. It is not advised to modify this value.
     *
     * @api
     */
    const ERROR_LEVEL = 'ERROR_LEVEL';

    /**
     * Specification:
     * - Sets which PHP error levels are not transformed into exceptions but logged only.
     *   This can be useful for production systems to not trigger exceptions for deprecations:
     *   $config[ErrorHandlerConstants::ERROR_LEVEL_LOG_ONLY] = E_DEPRECATED | E_USER_DEPRECATED;
     *
     * @api
     */
    const ERROR_LEVEL_LOG_ONLY = 'ERROR_LEVEL_LOG_ONLY';

    /**
     * Specification:
     * - Pattern for the link from the browser to the IDE.
     * - For MacOS the default `phpstorm://open?file=%s&line=%s` works.
     * - For Linux use e.g. `phpstorm://open?url=file://%s&line=%s` and the documentation tips.
     *
     * @api
     */
    const PATTERN_IDE_LINK = 'PATTERN_IDE_LINK';

    /**
     * Specification:
     * - Path to the project on local machine e.g. `/Users/foo/www/spryker/project`
     * - This is used to replace the path from server (/data/shop/development/current) with the user path.
     * - Enables file opening in IDE.
     *
     * @api
     */
    const USER_BASE_PATH = 'USER_BASE_PATH';

    /**
     * Specification:
     * - Path to the project on virtual machine, defaults to `/data/shop/development/current`
     * - This will be replaced with the user path.
     *
     * @api
     */
    const SERVER_BASE_PATH = 'SERVER_BASE_PATH';

    /**
     * Specification:
     * - When using the USER_BASE_PATH to open files in IDE, some IDEs require AJAX calls for it to work.
     *
     * @api
     */
    const AS_AJAX = 'AS_AJAX';

}
