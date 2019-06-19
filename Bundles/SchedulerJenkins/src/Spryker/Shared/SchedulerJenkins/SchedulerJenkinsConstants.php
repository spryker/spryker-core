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
     * - Defines configuration per each scheduler by name, following the structure:
     * [
     *     'schedulerId' => [
     *         SchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL => 'http://localhost:10007/',
     *         SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CREDENTIALS => ['username', 'password']
     *         SchedulerJenkinsConfig::SCHEDULER_JENKINS_CSRF_ENABLED => true
     *     ],
     * ...
     * ]
     *
     * @api
     */
    public const JENKINS_CONFIGURATION = 'SCHEDULER_JENKINS:JENKINS_CONFIGURATION';

    /**
     * Specification:
     * - Defines the path to Twig XML template for Jenkins API.
     *
     * @api
     */
    public const JENKINS_TEMPLATE_PATH = 'SCHEDULER_JENKINS:JENKINS_TEMPLATE_PATH';

    /**
     * Specification:
     * - Defines Jenkins logs rotation in days.
     *
     * @api
     */
    public const JENKINS_DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION = 'SCHEDULER_JENKINS:JENKINS_DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION';
}
