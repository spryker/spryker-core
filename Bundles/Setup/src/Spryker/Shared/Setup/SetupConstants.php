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
    /**
     * @deprecated Will be removed without replacement.
     *
     * @api
     */
    public const JENKINS_BASE_URL = 'JENKINS_BASE_URL';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @api
     */
    public const JENKINS_DIRECTORY = 'JENKINS_DIRECTORY';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @api
     */
    public const JENKINS_CSRF_PROTECTION_ENABLED = 'SETUP:JENKINS_CSRF_PROTECTION_ENABLED';
}
