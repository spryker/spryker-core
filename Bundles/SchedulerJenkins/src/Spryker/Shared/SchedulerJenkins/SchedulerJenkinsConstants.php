<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SchedulerJenkins;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SchedulerJenkinsConstants
{
    /**
     * Specification:
     * - Gets jenkins configuration.
     *
     * @api
     */
    public const JENKINS_CONFIGURATION = 'SCHEDULER_JENKINS:JENKINS_CONFIGURATION';

    /**
     * Specification:
     * - Sets jenkins csrf protection.
     *
     * @api
     */
    public const JENKINS_CSRF_PROTECTION_ENABLED = 'SCHEDULER_JENKINS:JENKINS_CSRF_PROTECTION_ENABLED';

    /**
     * Specification:
     * - Defines the path to jenkins xml template.
     *
     * @api
     */
    public const JENKINS_TEMPLATE_PATH = 'SCHEDULER_JENKINS:JENKINS_TEMPLATE_PATH';

    /**
     * Specification:
     * - Sets amount of day for log file rotation.
     *
     * @api
     */
    public const JENKINS_DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION = 'SCHEDULER_JENKINSs:JENKINS_DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION';
}
