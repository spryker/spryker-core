<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Url\Matcher;

interface UrlMatcherInterface
{
    /**
     * @param string $url
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\UrlCollectorStorageTransfer|bool
     */
    public function findUrl($url, $localeName);

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName);
}
