<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\JobWriter;

use Generated\Shared\Transfer\SchedulerJobResponseTransfer;

interface JenkinsJobWriterInterface
{
    /**
     * @param string $schedulerId
     * @param string $name
     * @param string $jobXmlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    public function createJenkinsJob(string $schedulerId, string $name, string $jobXmlTemplate): SchedulerJobResponseTransfer;

    /**
     * @param string $schedulerId
     * @param string $name
     * @param string $jobXmlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    public function updateJenkinsJob(string $schedulerId, string $name, string $jobXmlTemplate): SchedulerJobResponseTransfer;

    /**
     * @param string $schedulerId
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    public function deleteJenkinsJob(string $schedulerId, string $name): SchedulerJobResponseTransfer;

    /**
     * @param string $schedulerId
     * @param string $name
     * @param string $urlUpdateJobTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    public function updateJenkinsJobStatus(string $schedulerId, string $name, string $urlUpdateJobTemplate): SchedulerJobResponseTransfer;
}
