<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\Logger;

interface ElasticsearchLoggerInterface
{
    /**
     * @param array $payload
     * @param mixed|null $result
     *
     * @return void
     */
    public function log(array $payload, $result = null): void;

    /**
     * @return array
     */
    public function getLogs(): array;
}
