<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Dependency\Guzzle;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface SchedulerJenkinsToGuzzleInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array<string, mixed> $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface;
}
