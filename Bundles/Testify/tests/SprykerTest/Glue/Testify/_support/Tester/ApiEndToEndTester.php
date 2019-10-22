<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function formatUrl(string $url, $params = []): string
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
    public function formatFullUrl(string $url, $params = []): string
    {
        return rtrim(Config::get(TestifyConstants::GLUE_APPLICATION_DOMAIN) . '/' . $this->formatUrl($url, $params), '/');
    }
}
