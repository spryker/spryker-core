<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Dependency\Guzzle;

interface SearchElasticsearchToGuzzleClientInterface
{
    /**
     * @param string $uri
     * @param array $options
     *
     * @return int Response status code.
     */
    public function post(string $uri, array $options): int;
}
