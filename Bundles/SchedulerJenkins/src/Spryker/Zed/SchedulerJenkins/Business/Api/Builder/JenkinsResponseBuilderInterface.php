<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api\Builder;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;

interface JenkinsResponseBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function build(): SchedulerJenkinsResponseTransfer;

    /**
     * @param bool $status
     *
     * @return static
     */
    public function withStatus(bool $status);

    /**
     * @param string $message
     *
     * @return static
     */
    public function withMessage(string $message);

    /**
     * @param string $payload
     *
     * @return static
     */
    public function withPayload(string $payload);
}
