<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Collector\Matcher;

/**
 * @deprecated Use \Spryker\Client\Url\Matcher\UrlMatcherInterface instead.
 */
interface UrlMatcherInterface
{
    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array
     */
    public function matchUrl($url, $localeName);
}
