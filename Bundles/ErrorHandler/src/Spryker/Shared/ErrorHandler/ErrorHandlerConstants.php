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
     * - Absolute path to an html file which contains the error page for Zed. E.g. /var/www/public/Zed/error-page.html
     *
     * @api
     */
    const ZED_ERROR_PAGE = 'ZED_ERROR_PAGE';

    /**
     * Specification:
     * - Absolute path to an html file which contains the error page for Yves. E.g. /var/www/public/Yves/error-page.html
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
     * - Sets which PHP errors are reported.
     *
     * @api
     */
    const ERROR_LEVEL = 'ERROR_LEVEL';

}
