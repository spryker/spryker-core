<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Collector;

/**
 * @deprecated use \Spryker\Client\Url\UrlClientInterface
 */
interface CollectorClientInterface
{
    /**
     * Specification:
     * - Retrieves locale specific URL details from Storage if found. Then returns the resource that is referenced in that URL.
     *
     * @api
     *
     * @deprecated use \Spryker\Client\Url\UrlClientInterface:matchUrl
     *
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName);
}
