<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator;

interface JenkinsJobTemplateGeneratorInterface
{
    /**
     * @param array $job
     *
     * @return string
     */
    public function getJobTemplate(array $job): string;
}
