<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlueJsonApiConvention;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface GlueJsonApiConventionConstants
{
    /**
     * Specification:
     * - The domain name used for the current API application.
     *
     * @api
     *
     * @var string
     */
    public const GLUE_DOMAIN = 'GLUE_JSON_API_CONVENTION:GLUE_DOMAIN';
}
