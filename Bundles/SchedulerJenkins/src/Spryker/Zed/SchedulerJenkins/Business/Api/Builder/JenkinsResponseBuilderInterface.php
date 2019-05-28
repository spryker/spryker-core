<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api\Builder;

use Generated\Shared\Transfer\JenkinsResponseTransfer;

interface JenkinsResponseBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\JenkinsResponseTransfer
     */
    public function build(): JenkinsResponseTransfer;

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function withStatus(bool $status);

    /**
     * @param string $message
     *
     * @return $this
     */
    public function withMessage(string $message);

    /**
     * @param string $payload
     *
     * @return $this
     */
    public function withPayload(string $payload);
}
