<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SchedulerJenkins;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SchedulerJenkinsConfig extends AbstractSharedConfig
{
    public const SCHEDULER_JENKINS_BASE_URL = 'base_url';

    /**
     * Defines credentials for Jenkins Api, e.g ['username', 'password']
     */
    public const SCHEDULER_JENKINS_CREDENTIALS = 'credentials';

    /**
     * Defines if CSRF protection is enabled for Jenkins API calls.
     */
    public const SCHEDULER_JENKINS_CSRF_ENABLED = 'csrf';
}
