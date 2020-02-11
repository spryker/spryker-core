<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage;

use Generated\Shared\Transfer\UrlRedirectStorageTransfer;

interface UrlStorageClientInterface
{
    /**
     * Specification:
     * - Matches a URL and a locale to its storage entry.
     * - When localeName is null the localeName will be retrieved from the URL details.
     *
     * @api
     *
     * @param string $url
     * @param string|null $localeName
     *
     * @return array
     */
    public function matchUrl($url, $localeName);

    /**
     * Specification
     * - Gets the URL data from storage
     * - Returns UrlStorageTransfer with data
     * - If URL doesn't exist in storage, returns null
     *
     * @api
     *
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer|null
     */
    public function findUrlStorageTransferByUrl($url);

    /**
     * Specification:
     * - Looks up the redirect entity in key-value storage.
     * - Returns UrlRedirectStorageTransfer in case redirect is found in the storage and null otherwise.
     *
     * @api
     *
     * @param int $idRedirectUrl
     *
     * @return \Generated\Shared\Transfer\UrlRedirectStorageTransfer|null
     */
    public function findUrlRedirectStorageById(int $idRedirectUrl): ?UrlRedirectStorageTransfer;
}
