<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Dependency\TwigEnvironment;

interface SchedulerJenkinsToTwigEnvironmentInterface
{
    /**
     * @param string $template
     * @param array $options
     *
     * @return string
     */
    public function render(string $template, array $options): string;
}
