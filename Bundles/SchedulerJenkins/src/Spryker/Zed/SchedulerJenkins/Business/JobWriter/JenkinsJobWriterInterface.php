<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\JobWriter;

interface JenkinsJobWriterInterface
{
    /**
     * @param string $schedulerId
     * @param string $name
     * @param string $jobXmlTemplate
     *
     * @return string
     */
    public function createJenkinsJob(string $schedulerId, string $name, string $jobXmlTemplate): string;

    /**
     * @param string $schedulerId
     * @param string $name
     * @param string $jobXmlTemplate
     *
     * @return string
     */
    public function updateJenkinsJob(string $schedulerId, string $name, string $jobXmlTemplate): string;

    /**
     * @param string $schedulerId
     * @param string $name
     *
     * @return string
     */
    public function deleteJenkinsJob(string $schedulerId, string $name): string;
}
