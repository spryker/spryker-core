<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlueApplication;

interface GlueApplicationConstants
{
    /**
     * @api
     *
     * Specification:
     *   The domain name used for glue application
     */
    public const GLUE_APPLICATION_DOMAIN = 'GLUE_APPLICATION_DOMAIN';

    /**
     * @api
     *
     *  Specification:
     *    Is rest debug is enabled, will show exception stack traces instead of 500 errors
     */
    public const GLUE_APPLICATION_REST_DEBUG = 'GLUE_APPLICATION_REST_DEBUG';
}
