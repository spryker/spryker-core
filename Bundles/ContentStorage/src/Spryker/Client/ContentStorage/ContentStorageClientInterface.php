<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

interface ContentStorageClientInterface
{
    /**
     * Specification:
     * - Finds content item in the key-value storage.
     * - Resolves content type and executes data.
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return array|null
     */
    public function findContentById(int $idContent, string $localeName): ?array;
}
