<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Storage;

interface UrlStorageReaderInterface
{
    /**
     * @param string $url
     * @param string|null $localeName
     *
     * @return array
     */
    public function matchUrl($url, $localeName);

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer|null
     */
    public function findUrlStorageTransferByUrl($url);
}
