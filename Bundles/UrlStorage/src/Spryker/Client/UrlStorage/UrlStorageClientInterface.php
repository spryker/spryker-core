<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage;

interface UrlStorageClientInterface
{
    /**
     * Specification:
     * - Matches a URL and a locale to its storage entry
     *
     * @api
     *
     * @param string $url
     * @param string $localeName
     *
     * @return array
     */
    public function matchUrl($url, $localeName);

    /**
     * Specification
     * - Gets the URL data from storage
     *
     * @api
     *
     * @param string $url
     *
     * @return array
     */
    public function getUrlData($url);
}
