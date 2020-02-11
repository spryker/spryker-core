<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Tester;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Testify\TestifyConstants;
use SprykerTest\Shared\Testify\Tester\EndToEndTester;

abstract class ApiEndToEndTester extends EndToEndTester
{
    /**
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public function formatUrl(string $url, array $params = []): string
    {
        $refinedParams = [];

        foreach ($params as $key => $value) {
            $refinedParams['{' . $key . '}'] = urlencode($value);
        }

        return strtr($url, $refinedParams);
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public function formatFullUrl(string $url, array $params = []): string
    {
        return rtrim(Config::get(TestifyConstants::GLUE_APPLICATION_DOMAIN) . '/' . $this->formatUrl($url, $params), '/');
    }
}
