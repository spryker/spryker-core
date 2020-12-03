<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Logger;

interface ZedRequestLoggerInterface
{
    /**
     * @param string $url
     * @param array $payload
     * @param array $result
     *
     * @return void
     */
    public function log(string $url, array $payload, array $result): void;

    /**
     * @return array
     */
    public function getLogs(): array;
}
