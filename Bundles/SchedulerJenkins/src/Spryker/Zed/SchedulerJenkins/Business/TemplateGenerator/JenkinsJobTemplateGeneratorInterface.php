<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator;

use Generated\Shared\Transfer\SchedulerJobTransfer;

interface JenkinsJobTemplateGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $schedulerJobTransfer
     *
     * @return string
     */
    public function getJobTemplate(SchedulerJobTransfer $schedulerJobTransfer): string;
}
