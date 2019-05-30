<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api\Builder;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;

class JenkinsResponseBuilder implements JenkinsResponseBuilderInterface
{
    /**
     * @var bool
     */
    protected $status = false;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var string
     */
    protected $payload = '';

    /**
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function build(): SchedulerJenkinsResponseTransfer
    {
        $responseTransfer = new SchedulerJenkinsResponseTransfer();

        $responseTransfer
            ->setStatus($this->status)
            ->setMessage($this->message)
            ->setPayload($this->payload);

        return $responseTransfer;
    }

    /**
     * @param bool $status
     *
     * @return static
     */
    public function withStatus(bool $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return static
     */
    public function withMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param string $payload
     *
     * @return static
     */
    public function withPayload(string $payload)
    {
        $this->payload = $payload;

        return $this;
    }
}
