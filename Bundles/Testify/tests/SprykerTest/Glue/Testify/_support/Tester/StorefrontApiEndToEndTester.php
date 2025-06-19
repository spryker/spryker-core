<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Tester;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Testify\TestifyConstants;

class StorefrontApiEndToEndTester extends AbstractEndToEndTester
{
    /**
     * @param string $url
     * @param array<string, mixed> $params
     *
     * @return string
     */
    public function formatFullUrl(string $url, array $params = []): string
    {
        return rtrim(Config::get(TestifyConstants::GLUE_STOREFRONT_API_DOMAIN) . '/' . $this->formatUrl($url, $params), '/');
    }
}
