<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Collector;

/**
 * @deprecated Use \Spryker\Client\Url\UrlClientInterface instead.
 */
interface CollectorClientInterface
{
    /**
     * Specification:
     * - Retrieves locale specific URL details from Storage if found. Then returns the resource that is referenced in that URL.
     *
     * @api
     *
     * @deprecated Use \Spryker\Client\Url\UrlClientInterface:matchUrl() instead.
     *
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName);
}
