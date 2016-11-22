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

}
