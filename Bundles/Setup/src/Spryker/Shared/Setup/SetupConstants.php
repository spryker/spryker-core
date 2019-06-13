<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Setup;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SetupConstants
{
    public const JENKINS_BASE_URL = 'JENKINS_BASE_URL';
    public const JENKINS_DIRECTORY = 'JENKINS_DIRECTORY';
    public const JENKINS_CSRF_PROTECTION_ENABLED = 'SETUP:JENKINS_CSRF_PROTECTION_ENABLED';

    /**
     * Specification:
     * - Defines the mode to enable/disable scheduler for non-production environment.
     *
     * @api
     */
    public const ENABLE_SCHEDULER = 'SETUP:ENABLE_SCHEDULER';

    /**
     * Specification:
     * - Defines the mode to enable jenkins deploy vars.
     *
     * @api
     */
    public const ENABLE_DEPLOY_VARS = 'SETUP:ENABLE_DEPLOY_VARS';
}
