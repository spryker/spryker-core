<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ErrorHandlerConstants
{
    /**
     * Specification:
     * - Defines if the pretty error handler is enabled.
     * - Should only be enabled in development mode.
     *
     * @api
     */
    public const IS_PRETTY_ERROR_HANDLER_ENABLED = 'ERROR_HANDLER:IS_PRETTY_ERROR_HANDLER_ENABLED';

    /**
     * Specification:
     * - Absolute path to an HTML file which contains the error page for Zed. E.g. /var/www/public/Zed/error-page.html
     *
     * @api
     */
    public const ZED_ERROR_PAGE = 'ZED_ERROR_PAGE';

    /**
     * Specification:
     * - Absolute path to an HTML file which contains the error page for Yves. E.g. /var/www/public/Yves/error-page.html
     *
     * @api
     */
    public const YVES_ERROR_PAGE = 'YVES_ERROR_PAGE';

    /**
     * Specification:
     * - Class name of class which implements ErrorRendererInterface and should be used to render a given exception.
     *
     * @api
     */
    public const ERROR_RENDERER = 'ERROR_RENDERER';

    /**
     * Specification:
     * - Sets which PHP error levels are reported. It is not advised to modify this value.
     *
     * @api
     */
    public const ERROR_LEVEL = 'ERROR_LEVEL';

    /**
     * Specification:
     * - Sets which PHP error levels are not transformed into exceptions but logged only.
     *   This can be useful for production systems to not trigger exceptions for deprecations:
     *   $config[ErrorHandlerConstants::ERROR_LEVEL_LOG_ONLY] = E_DEPRECATED | E_USER_DEPRECATED;
     *
     * @api
     */
    public const ERROR_LEVEL_LOG_ONLY = 'ERROR_LEVEL_LOG_ONLY';

    /**
     * Specification:
     * - Pattern for the link from the browser to the IDE.
     * - The default `phpstorm://open?file=%s&line=%s` works for most OS.
     *
     * @api
     */
    public const PATTERN_IDE_LINK = 'PATTERN_IDE_LINK';

    /**
     * Specification:
     * - Path to the project on local machine e.g. `/Users/foo/www/spryker/project`
     * - This is used to replace the path from server (/data/shop/development/current) with the user path.
     * - Enables file opening in IDE.
     *
     * @api
     */
    public const USER_BASE_PATH = 'USER_BASE_PATH';

    /**
     * Specification:
     * - Path to the project on virtual machine, defaults to `/data/shop/development/current`
     * - This will be replaced with the user path.
     *
     * @api
     */
    public const SERVER_BASE_PATH = 'SERVER_BASE_PATH';

    /**
     * Specification:
     * - When using the USER_BASE_PATH to open files in IDE, some IDEs require AJAX calls for it to work.
     *
     * @api
     */
    public const AS_AJAX = 'AS_AJAX';

    /**
     * Specification:
     * - Set php.ini config 'display_errors'
     * - type bool
     * - default false
     *
     * @api
     */
    public const DISPLAY_ERRORS = 'DISPLAY_ERRORS';

    /**
     * Specification:
     * - Class name of the class which implements `ErrorRendererInterface` and should be used to render a given exception in Glue.
     *
     * @api
     */
    public const API_ERROR_RENDERER = 'ERROR_HANDLER:API_ERROR_RENDERER';
}
