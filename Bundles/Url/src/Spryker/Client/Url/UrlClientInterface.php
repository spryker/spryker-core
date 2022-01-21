<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Url;

interface UrlClientInterface
{
    /**
     * Specification:
     * - Retrieves locale specific URL details from Storage if found.
     *
     * @api
     *
     * @param string $url
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\UrlCollectorStorageTransfer|false
     */
    public function findUrl($url, $localeName);

    /**
     * Specification:
     * - Retrieves locale specific URL details from Storage if found. Then returns the resource that is referenced in that URL.
     *
     * @api
     *
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName);
}
