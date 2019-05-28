<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api\Builder;

use Generated\Shared\Transfer\JenkinsResponseTransfer;

class JenkinsResponseBuilder implements JenkinsResponseBuilderInterface
{
    /**
     * @var bool
     */
    protected $status;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $payload;

    /**
     * @return \Generated\Shared\Transfer\JenkinsResponseTransfer
     */
    public function build(): JenkinsResponseTransfer
    {
        $schedulerResponseTransfer = new JenkinsResponseTransfer();

        $schedulerResponseTransfer
            ->setStatus($this->status)
            ->setMessage($this->message)
            ->setPayload($this->payload);

        return $schedulerResponseTransfer;
    }

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function withStatus(bool $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function withMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param string $payload
     *
     * @return $this
     */
    public function withPayload(string $payload)
    {
        $this->payload = $payload;

        return $this;
    }
}
